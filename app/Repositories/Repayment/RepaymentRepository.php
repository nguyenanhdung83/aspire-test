<?php

namespace App\Repositories\LoanRepayment;

use App\Models\Repayment;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Traits\Conditionable;

class RepaymentRepository extends BaseRepository implements RepaymentInterface
{
    public function __construct(Repayment $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $repaymentId
     * @param int $userId without $userId can return all repayments
     * @return Model|Conditionable|mixed|object|null
     */
    public function detail(int $repaymentId, int $userId = 0)
    {
        return $this->model()->query()
            ->when($userId > 0, function ($query, $userId) {
                return $query->join('loans', 'loans.id', 'repayments.loan_id')
                    ->where('loans.user_id', $userId);
            })
            ->where('repayments.id', $repaymentId)
            ->select('repayments.*')
            ->first();
    }

    /**
     * @param $loanId
     * @param int $userId without $userId can return all repayments
     * @return array|Collection|Conditionable[]
     */
    public function getByLoanId($loanId, $userId = 0)
    {
        return $this->model()->query()
            ->join('loans', 'loans.id', 'repayments.loan_id')
            ->when($userId > 0, function ($query, $userId) {
                return $query->where('loans.user_id', $userId);
            })
            ->where('repayments.loan_id', $loanId)
            ->get('repayments.*');
    }

    /**
     * @param $loanId
     * @return int|mixed
     */
    public function getTotalRepaidAmount($loanId): int
    {
        return DB::table('repayments')
            ->where('loan_id', $loanId)
            ->sum('amount');
    }

}
