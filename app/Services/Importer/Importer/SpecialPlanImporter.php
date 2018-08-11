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
                if ($this->isItemValid($item)) {
                    $this->items[] = $item;
                } else {
                    $this->incorrectItems[] = $rawItem;
                }
            }
        }
    }

    private function getItemFromRawItem(array $rawItem): array
    {
        return [
            'fire_level_id' => $this->getFireLevelIdByName((string)array_get($rawItem, 0)),
            'city_area_id' => $this->getAreaIdByName((string)array_get($rawItem, 1)),
            'object_name' => array_get($rawItem, 2),
            'fire_department_id' => $this->getFireDepartmentIdByName((string)array_get($rawItem, 3)),
            'operational_plan_id' => $this->getOperationalPlanId((string)array_get($rawItem, 4)),
            'location' => array_get($rawItem, 5)
        ];
    }

    private function isItemValid(array $item): bool
    {
        return !Validator::make($item, [
            'fire_level_id' => 'required|integer|min:1',
            'city_area_id' => 'required|integer|min:1',
            'object_name' => 'required|string|max:255',
            'fire_department_id' => 'required|integer|min:1',
            'operational_plan_id' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
        ])->fails();
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
        $id = array_get($this->areas, $name, 0);

        if (!$id) {
            $item = (new CityArea)->where('name', 'like', $name)->first();
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
    private function getFireDepartmentIdByName(string $name): int
    {
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
