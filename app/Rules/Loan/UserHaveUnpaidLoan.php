<?php

declare(strict_types=1);

namespace App\Rules\Loan;

use App\Services\LoanService;
use Illuminate\Contracts\Validation\Rule;

class UserHaveUnpaidLoan implements Rule
{

    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $loanService = app(LoanService::class);

        return !$loanService->existsUnCompletedLoan($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('loan.user_had_unpaid_loan');
    }
}
