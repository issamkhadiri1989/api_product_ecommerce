<?php

namespace App\Enum;

enum CartStatus: string
{
    case PLACED = 'placed';

    case PENDING = 'pending';

    case CANCELED = 'canceled';

    case SHIPPED = 'shipped';
}
