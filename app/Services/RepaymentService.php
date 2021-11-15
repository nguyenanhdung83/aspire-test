<?php
namespace App\Services;

use App\Repositories\LoanRepayment\RepaymentInterface;

class RepaymentService extends BaseService
{
    public function __construct(RepaymentInterface $repository)
    {
        parent::__construct($repository);

    }

}
