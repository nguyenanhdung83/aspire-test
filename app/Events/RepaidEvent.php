<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Repayment;
use Illuminate\Queue\SerializesModels;

class RepaidEvent
{
    use SerializesModels;

    public $repayment;

    /**
     * Create a new event instance.
     *
     * @param
     */
    public function __construct(Repayment $repayment)
    {
        $this->repayment = $repayment;
    }
}
