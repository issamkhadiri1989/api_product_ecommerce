<?php

namespace App\Enum;

enum InventoryStatusEnum: string
{
    case IN_STOCK = 'INSTOCK';

    case LOW_STOCK = 'LOWSTOCK';

    case OUT_OF_STOCK = 'OUTOFSTOCK';
}
