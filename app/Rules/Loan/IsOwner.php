<?php

declare(strict_types=1);

namespace App\Rules\Loan;

use App\Services\LoanService;
use Illuminate\Contracts\Validation\Rule;

class IsOwner implements Rule
{
    /**
     * @var array
     */
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
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

        return $loanService->isMyLoan($value, $this->userId);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('loan.not_owner');
    }
}
