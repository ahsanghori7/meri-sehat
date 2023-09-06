<?php   namespace App\Http\Requests;

use Illuminate\{
    Foundation\Http\FormRequest, Http\JsonResponse, Validation\ValidationException, Contracts\Validation\Validator, Http\Exceptions\HttpResponseException
};

class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json([
                'message' => head(head($errors)),
                'error' => true,
                'code' => 422,
            ],
        JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
