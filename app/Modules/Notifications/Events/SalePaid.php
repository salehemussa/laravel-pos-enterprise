<?php

namespace App\Modules\Notifications\Events;

use App\Models\Sale;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SalePaid
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Sale $sale) {}


}
