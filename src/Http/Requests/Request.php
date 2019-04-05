<?php

namespace Katsana\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Katsana\Exceptions\ValidationException;

abstract class Request extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @throws \Billplz\Laravel\Exceptions\ValidationException
     *
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException($validator, null, $this->errorBag);
    }
}
