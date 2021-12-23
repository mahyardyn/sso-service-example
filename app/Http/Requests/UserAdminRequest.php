<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use App\Lib\Logger\Logger;
use Illuminate\Support\Facades\Gate;

class UserAdminRequest extends FormRequest
{
    static public bool $manageGroup = false;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return match (request()::segment(4)) {
            'create' => [
                'mobile' => ['required', 'regex:/^09[0-3][0-9][0-9]{3}[0-9]{4}/'],
                'name' => ['required', 'string'],
                'family' => ['required', 'string']
            ],
            'edit' => [
                '_id' => ['required', 'exists:App\Models\User,_id'],
                'mobile' => [
                    'regex:/^09[0-3][0-9][0-9]{3}[0-9]{4}/',
                    'unique:App\Models\User,mobile'
                ],
                'name' => ['string'],
                'family' => ['string']
            ],
            'delete' => [
                '_id' => [
                    'required',
                    'exists:App\Models\User,_id'
                ]
            ],
            default => [],
        };
    }

    protected function validationFailed(): void
    {
        // Generate log
        Logger::set('error', request()::segment(4), $this->validator->errors()->all());

        // Send 422 status code to Exception handler
        abort(422, serialize($this->validator->errors()->all()));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        // set true if user have manage-group permission
        if(request()::segment(4) === 'edit' and !Gate::denies('manage-group'))
            self::$manageGroup = true;

        // return false if user dont have permission
        return !Gate::denies(request()::segment(4) . '-user');
    }

    /**
     * Run if authorization is not successfully
     *
     * @return void
     */
    protected function failedAuthorization(): void
    {
        // Generate log
        Logger::set('error', request()::segment(4), 'Forbidden');

        // Send 403 status code to Exception handler
        abort(403);
    }


}
