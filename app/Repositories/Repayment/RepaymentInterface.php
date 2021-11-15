<?php

namespace App\Repositories\LoanRepayment;

use App\Repositories\BaseRepositoryInterface;

interface RepaymentInterface extends BaseRepositoryInterface
{
    public function getByLoanId($loanId, $userId = 0);

    public function getTotalRepaidAmount($loanId);
}
