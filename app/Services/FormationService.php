<?php

namespace App\Services;


use App\FireDepartment;
use App\Models\FormationPersonsItem;
use App\Models\FormationTechItem;
use Illuminate\Support\Collection;

class FormationService
{
    /**
     * @var array
     */
    private $excludedDepartments = [
        'ПЧ-13'
    ];

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getExcludedDepartments(): Collection
    {
        return collect((new FireDepartment)->whereIn('title', $this->excludedDepartments)->get());
    }

    /**
     * @param Collection $departments
     * @param array $peopleFields
     * @param array $techFields
     * @param Collection $peopleReport
     * @param Collection $techReport
     * @return array
     */
    public function getSumArrayByDepartmentsArray(Collection $departments, array $peopleFields, array $techFields, Collection $peopleReport, Collection $techReport, $report = null): array
    {
        $sumArray = [
            'people' => [],
            'tech' => []
        ];
        foreach ($departments->toArray() as $department) {
            foreach ($peopleFields as $peopleField) {
                if (!isset($sumArray['people'][$peopleField])) {
                    $sumArray['people'][$peopleField] = 0;
                }
                $sumArray['people'][$peopleField] += isset($peopleReport[$department['id']]) ? (float)$peopleReport[$department['id']]->{$peopleField} : 0;
            }

            foreach ($techFields as $techField) {
                if (!isset($sumArray['tech'][$techField])) {
                    $sumArray['tech'][$techField] = 0;
                }
                $sumArray['tech'][$techField] += isset($techReport[$department['id']]) ? (float)$techReport[$department['id']]->{$techField} : 0;
            }
        }
        // сумма АСВ/ДАСК
        if($report) {
            $sumArray['tech']['asv_dask'] = $report->sumTechTwo('asv','dask');
        }

        if($report) {
            $sumArray['tech']['dvr'] = $report->sumDvr();
        }

        return $sumArray;
    }
}
