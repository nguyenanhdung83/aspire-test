<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\BaseFormRequest;
use App\Rules\Loan\IsOwner;
use App\Rules\Loan\IsPaidOff;
use App\Rules\Loan\UserHaveUnpaidLoan;

class LoanShowRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationData = $this->validationData();

        return [
            'loan_id' => [new IsOwner($validationData['user_id'])],
        ];
    }

    /**
     * @return array
     */
    public function validationData(): array
    {
        $data['loan_id'] = request('id');
        $data['user_id'] = auth()->user()->id;
        return $data;
    }
}
