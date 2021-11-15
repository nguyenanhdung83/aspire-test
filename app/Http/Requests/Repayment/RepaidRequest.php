<?php

namespace App\Http\Requests\Repayment;

use App\Http\Requests\BaseFormRequest;
use App\Rules\Loan\IsOwner;
use App\Rules\Loan\IsPaidOff;

class RepaidRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $validationData = $this->validationData();

        return [
            'amount' => ['bail','required','numeric','min:1'],
            'loan_id' => ['bail', new IsOwner($validationData['user_id']), new IsPaidOff()],
        ];
    }

    /**
     * @return array
     */
    public function validationData(): array
    {
        $data = $this->all();
        $data['loan_id'] = request('id');
        $data['user_id'] = auth()->user()->id;

        return $data;
    }
}
