<?php


namespace App\Exceptions;


use Exception;
use Illuminate\Contracts\Validation\Validator;

class AspireValidationException extends Exception
{
    protected $validator;

    protected $code = 422;

    public function __construct(Validator $validator) {
        parent::__construct();
        $this->validator = $validator;
    }

    public function render() {
        return response()->json([
            "status" => false,
            "errors" => $this->validator->errors()
        ], $this->code);
    }
}
