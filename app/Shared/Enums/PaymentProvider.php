<?php

namespace App\Shared\Enums;

enum PaymentProvider: string
{
    case CASH = 'cash';
    case CARD = 'card';
    case MPESA = 'mpesa';
    case TIGOPESA = 'tigopesa';
    case AIRTELMONEY = 'airtelmoney';
}
