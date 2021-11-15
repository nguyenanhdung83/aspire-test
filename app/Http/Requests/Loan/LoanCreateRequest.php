<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\BaseFormRequest;
use App\Rules\Loan\UserHaveUnpaidLoan;

class LoanCreateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // maybe have many validate when user want to apply new loan
        // but in this case, I just check user must completed all loan
        return [
            'amount' => 'required|numeric|min:1000',
            'term' => 'required|integer|min:4',
            'user_id' => [new UserHaveUnpaidLoan()],
        ];
    }

    /**
     * @return array
     */
    public function validationData(): array
    {
        $data = $this->all();
        $data['user_id'] = auth()->user()->id;

        return $data;
    }
}
