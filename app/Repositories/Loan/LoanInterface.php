<?php

namespace App\Repositories\Loan;

use App\Repositories\BaseRepositoryInterface;

interface LoanInterface extends BaseRepositoryInterface
{
    public function list(int $userId = 0);

    public function existsUnCompletedLoan(int $userId);

    public function isRepaymentCompleted(int $loadId);

    public function detail(int $loanId, int $userId = 0);

    public function isMyLoan(int $loanId, int $userId);
}
