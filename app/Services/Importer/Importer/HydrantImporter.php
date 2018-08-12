<?php

namespace App\Services\Importer\Importer;


use App\FireDepartment;
use Illuminate\Support\Facades\Validator;

class HydrantImporter implements ImporterInterface
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
    private $fireDepartments;

    /**
     * SpecialPlanImporter constructor.
     */
    public function __construct()
    {
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
            'number' => array_get($rawItem, 0),
            'address' => array_get($rawItem, 1),
            'lat' => array_get($rawItem, 2, 0),
            'long' => array_get($rawItem, 3, 0),
            'specification' => array_get($rawItem, 4),
            'fire_department_id' => $this->getFireDepartmentIdByName((string)array_get($rawItem, 5)),
            'active' => (int)array_get($rawItem, 6, 1),
        ];
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
     * @param $item
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function getValidator($item)
    {
        return Validator::make($item, [
            'number' => 'max:255',
            'address' => 'required|string|max:1000',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'specification' => 'required|string|max:65000',
            'fire_department_id' => 'required|integer|min:1',
            'active' => 'required|integer|min:0|max:1'
        ]);
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

}
