<?php

namespace App\Enums;

enum RowKeysType: string
{
    case TYPE = 'rubrika';
    case CATEGORY_TITLE = 'kategoriia_tovara';
    case MANUFACTURER = 'proizvoditel';
    case DESCRIPTION = 'opisanie_tovara';
    case NAME = 'naimenovanie_tovara';
    case CODE_OF_MODEL = 'kod_modeli_artikul_proizvoditelia';
    case PRICE = 'cena_rozn_grn';
    case GUARANTY = 'garantiia';
    case AVAILABILITY = 'nalicie';
    case AVAILABILITY_TRUE = 'есть в наличие';
}
