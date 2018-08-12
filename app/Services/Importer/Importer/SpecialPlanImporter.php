<?php

namespace App\Services\Importer\Importer;


use App\Dictionary\CityArea;
use App\Dictionary\FireLevel;
use App\FireDepartment;
use App\Models\OperationalPlan;
use Illuminate\Support\Facades\Validator;

class SpecialPlanImporter implements ImporterInterface
{
    use CommonImporterTrait;

    /**
     * @var array
     */
    private $items = [];

    /**
     * @var array
     */
    private $incorrectItems = [];

    /**
     * @var array
     */
    private $fireLevels;

    /**
     * @var array
     */
    private $areas;

    /**
     * @var array
     */
    private $fireDepartments;

    /**
     * SpecialPlanImporter constructor.
     */
    public function __construct()
    {
        $this->fireLevels = (new FireLevel)->get()->keyBy('name')->map(function ($item) {
            return (int)$item->id;
        })->toArray();

        $this->areas = (new CityArea)->get()->keyBy('name')->map(function ($item) {
            return (int)$item->id;
        })->toArray();

        $this->fireDepartments = (new FireDepartment())->get()->keyBy('title')->map(function ($item) {
            return (int)$item->id;
        })->toArray();
    }

    /**
     * @param $filePath
     * @return ImporterInterface
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function loadFile($filePath): ImporterInterface
    {
        $this->items = [];
        $this->incorrectItems = [];
        $this->separateItems($this->parseItems($filePath));
        return $this;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return array
     */
    public function getIncorrectItems(): array
    {
        return $this->incorrectItems;
    }


    /**
     * @param array $items
     */
    private function separateItems(array $items): void
    {
        foreach ($items as $index => $rawItem) {
            if ($index > 0) {
                $item = $this->getItemFromRawItem($rawItem);
                $validator = $this->getValidator($item);
                if (!$validator->fails()) {
                    $this->items[] = $item;
                } else {
                    $rawItem['errors'] = $this->getErrorsMessage($validator);
                    $this->incorrectItems[] = $rawItem;
                }
            }
        }
    }

    /**
     * @param array $rawItem
     * @return array
     */
    private function getItemFromRawItem(array $rawItem): array
    {
        return [
            'fire_level_id' => $this->getFireLevelIdByName((string)array_get($rawItem, 0)),
            'city_area_id' => $this->getAreaIdByName((string)array_get($rawItem, 1)),
            'object_name' => array_get($rawItem, 2),
            'fire_department_id' => $this->getFireDepartmentIdByName((string)array_get($rawItem, 3)),
            'operational_plan_id' => $this->getOperationalPlanId((string)array_get($rawItem, 4)),
            'location' => array_get($rawItem, 5),
            'year_of_development' => array_get($rawItem, 6),
        ];
    }

    /**
     * @param $item
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function getValidator($item)
    {
        return Validator::make($item, [
            'fire_level_id' => 'required|integer|min:1',
            'city_area_id' => 'required|integer|min:1',
            'object_name' => 'required|string|max:255',
            'fire_department_id' => 'required|integer|min:1',
            'operational_plan_id' => 'required|integer|min:1',
            'location' => 'required|string'
        ]);
    }

    /**
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return string
     */
    private function getErrorsMessage(\Illuminate\Contracts\Validation\Validator $validator): string
    {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $key => $value) {
            switch ($key) {
                case 'fire_level_id':
                    $errors[] = 'Не удалось распознать ранг';
                    break;
                case 'city_area_id':
                    $errors[] = 'Не удалось распознать район';
                    break;
                case 'fire_department_id':
                    $errors[] = 'Не удалось распознать микроучасток';
                    break;
                default:
                    $errors[] = implode(', ', $value);
            }
        }

        return implode(', ', $errors);
    }

    /**
     * @param string $name
     * @return int
     */
    private function getFireLevelIdByName(string $name): int
    {
        $id = array_get($this->fireLevels, $name, 0);

        if (!$id) {
            $item = (new FireLevel)->where('name', 'like', $name)->first();
            if ($item) {
                $id = $item->id;
            }
        }

        return $id;
    }

    /**
     * @param string $name
     * @return int
     */
    private function getAreaIdByName(string $name): int
    {
        $name = mb_strtolower($name);
        $name = str_replace('район', '', $name);
        $name = trim($name);

        $id = array_get($this->areas, $name, 0);

        if (!$id) {
            $item = (new CityArea)->where('name', 'like', '%' . $name . '%')->first();
            if ($item) {
                $id = $item->id;
                $this->areas[$name] = $item->id;
            }
        }

        return $id;
    }

    /**
     * @param string $name
     * @return int
     */
    private function getFireDepartmentIdByName(string $name): int
    {
        $name = mb_strtolower($name);
        $name = str_replace('микроучасток', '', $name);
        $name = trim($name);

        $id = array_get($this->fireDepartments, $name, 0);

        if (!$id) {
            $item = (new FireDepartment)->where('title', 'like', $name)->first();
            if ($item) {
                $id = $item->id;
            }
        }

        return $id;
    }

    /**
     * @param string $name
     * @return int
     */
    private function getOperationalPlanId(string $name): int
    {
        return (new OperationalPlan)->firstOrCreate(['name' => $name])->getAttribute('id');
    }
}
