<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use App\Lib\Logger\Logger;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        // read controller method -> http://127.0.0.1:8001/api/en/tasks/{create} and return intended rules
        return match (request()::segment(3)) {
            'otp' => [
                'mobile' => ['required', 'regex:/09[0-3][0-9][0-9]{3}[0-9]{4}/']
            ],
            'update' => [
                'name' => ['required', 'string'],
                'family' => ['required', 'string']
            ],
            default => [],
        };
    }

    protected function validationFailed(): void
    {
        // Generate log
        Logger::set('error', request()::segment(3), $this->validator->errors()->all());

        // Send 422 status code to Exception handler
        abort(422, serialize($this->validator->errors()->all()));
    }
}
