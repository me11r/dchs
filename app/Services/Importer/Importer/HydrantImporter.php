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
                if ($this->isItemValid($item)) {
                    $this->items[] = $item;
                } else {
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
            'lat' => (float)array_get($rawItem, 2, 0),
            'long' => (float)array_get($rawItem, 3, 0),
            'specification' => array_get($rawItem, 4),
            'fire_department_id' => $this->getFireDepartmentIdByName((string)array_get($rawItem, 5)),
            'active' => (int)array_get($rawItem, 6, 1),
        ];
    }

    /**
     * @param array $item
     * @return bool
     */
    private function isItemValid(array $item): bool
    {
        return !Validator::make($item, [
            'number' => 'max:255',
            'address' => 'required|string|max:1000',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'specification' => 'required|string|max:65000',
            'fire_department_id' => 'required|integer|min:1',
            'active' => 'required|integer|min:0|max:1'
        ])->fails();
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

}
