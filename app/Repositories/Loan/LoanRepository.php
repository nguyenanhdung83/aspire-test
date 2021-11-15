<?php

namespace App\Repositories\Loan;

use App\Models\Loan;
use App\Repositories\BaseRepository;
use App\Repositories\Loan\LoanInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Conditionable;

class LoanRepository extends BaseRepository implements LoanInterface
{
    public function __construct(Loan $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int $userId without $userId can return all loans
     * @return LengthAwarePaginator
     */
    public function list(int $userId = 0)
    {
        return $this->model()->query()
            ->when($userId > 0, function ($query, $userId) {
                return $query->where('user_id', $userId);
            })
            ->with('repayments')
            ->paginate();
    }

    /**
     * @param int $loanId
     * @param int $userId without $userId can show any loan (for admin example)
     * @return Builder|Model|Conditionable|mixed|object|null
     */
    public function detail(int $loanId, int $userId = 0)
    {
        return $this->model()->query()
            ->where('id', $loanId)
            ->when($userId > 0, function ($query, $userId) {
                return $query->where('user_id', $userId);
            })
            ->with('repayments')
            ->first();
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function existsUnCompletedLoan(int $userId): bool
    {
        return $this->model()->query()
            ->where([
                'user_id' => $userId,
                'repayment_completed' => Loan::REPAYMENT_COMPLETED['not_yet']
            ])
            ->exists();
    }

    /**
     * @param int $loanId
     * @return bool
     */
    public function isRepaymentCompleted(int $loanId): bool
    {
        return $this->model()->query()
            ->where([
                'id' => $loanId,
                'repayment_completed' => Loan::REPAYMENT_COMPLETED['yes']
            ])
            ->exists();
    }

    /**
     * @param int $loanId
     * @param int $userId
     * @return bool
     */
    public function isMyLoan(int $loanId, int $userId): bool
    {
        return $this->model()->query()
            ->where([
                'id' => $loanId,
                'user_id' => $userId
            ])
            ->exists();
    }
}
