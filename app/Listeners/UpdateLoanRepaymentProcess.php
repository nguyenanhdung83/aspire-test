<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\RepaidEvent;
use App\Services\LoanService;

//TODO: this listener should implements ShouldQueue
class UpdateLoanRepaymentProcess
{
    private $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Handle the event.
     *
     * @param RepaidEvent $event
     */
    public function handle(RepaidEvent $event): void
    {
        $repayment = $event->repayment;
        try {
            $this->loanService->calculateRepaymentProcess((int)$repayment->loan_id);
        } catch (\Exception $exception) {
            report($exception);
        }
    }
}
