<?php

namespace App\Http\Requests;

use App\Traits\HttpResponses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest {

  use HttpResponses;

  protected function failedValidation(Validator $validator) {
    throw new HttpResponseException(
      $this->error($validator->errors(), 'Ocurrío un error al validar los datos', 400)
    );
  }
}
