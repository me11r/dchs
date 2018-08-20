<?php

namespace App\Enums;


class FormationOrganisation
{
    public const ROSO = 'roso';
    public const MEDICAL = 'medical';
    public const MUDFLOW_PROTECTION = 'mudflow';
    public const AIR_RESCUE = 'air_rescue';
    public const ORT_SERT = 'ort_sert';
    public const DCHS_ALMATY = 'dchs_almaty';

    public static $namesMapping = [
        self::ROSO => 'ГУ «РОСО КЧС МВД РК»',
        self::MEDICAL => 'ГУ «Центр медицины катастроф»',
        self::MUDFLOW_PROTECTION => 'ГУ «Казселезащита»',
        self::AIR_RESCUE => 'АО "Казавиаспас"',
        self::ORT_SERT => 'АО "Өртсөндіруші"',
        self::DCHS_ALMATY => 'ДЧС г.Алматы'
    ];

    public static function getNameByType(string $type): string
    {
        return self::$namesMapping[$type];
    }
}
