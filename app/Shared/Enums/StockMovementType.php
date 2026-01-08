<?php

namespace App\Shared\Enums;

enum StockMovementType: string
{
    case IN = 'IN';
    case OUT = 'OUT';
    case ADJUST = 'ADJUST';
}
