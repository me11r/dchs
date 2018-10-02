<?php

namespace App\Http\Middleware\Rights;

use App\Enums\FormationOrganisation;
use App\Exceptions\AccessDeniedException;
use App\Right;
use Closure;
use Illuminate\Http\Request;

class FormationRecord
{
    use RightsTrait;


    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws AccessDeniedException
     */
    public function handle(Request $request, Closure $next)
    {
        $organisation = $this->getOrganisationNameFromRequest($request);
        $result = false;

        switch ($organisation) {
            case FormationOrganisation::ROSO:
                $result = $this->userHasRight(Right::CAN_ACCESS_FORMATION_REPORT_ROSO);
                break;
            case FormationOrganisation::MEDICAL:
                $result = $this->userHasRight(Right::CAN_ACCESS_FORMATION_REPORT_CMK);
                break;
            case FormationOrganisation::MUDFLOW_PROTECTION:
                $result = $this->userHasRight(Right::CAN_ACCESS_FORMATION_REPORT_MUDFLOW_PROTECTION);
                break;
            case FormationOrganisation::AIR_RESCUE:
                $result = $this->userHasRight(Right::CAN_ACCESS_FORMATION_REPORT_AIR_RESCUE);
                break;
            case FormationOrganisation::ORT_SERT:
                $result = $this->userHasRight(Right::CAN_ACCESS_FORMATION_REPORT_ORTSERT);
                break;
            case FormationOrganisation::EMERGENCY:
                $result = $this->userHasRight(Right::CAN_ACCESS_FORMATION_EMERGENCY_ALMATY);
                break;
            case FormationOrganisation::DCHS_ALMATY:
            default:
                $result = $this->userHasRight(Right::CAN_ACCESS_FORMATION_DCHS_ALMATY);
                break;
        }

        if ($result) {
            return $next($request);
        }

        throw new AccessDeniedException();
    }

    private function getOrganisationNameFromRequest(Request $request)
    {
        $result = $request->route('organisation');
        if (!$result) {
            $formationRecord = \App\Models\FormationRecord::find($request->route('formation_record'));
            if ($formationRecord) {
                $result = $formationRecord->organisation;
            }
        }
        return $result;
    }
}
