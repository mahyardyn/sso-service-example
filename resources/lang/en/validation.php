<?php

/*
|--------------------------------------------------------------------------
| Custom Validation Language Lines
|--------------------------------------------------------------------------
|
| Here you may specify custom validation messages for attributes using the
| convention "attribute.rule" to name the lines. This makes it quick to
| specify a specific custom language line for a given attribute rule.
|
*/

return [
    'required'             => 'The :attribute is required',
    'exists'               => 'There are no :attribute with the entered parameter',
    'unique'               => 'The :attribute has already been taken.',
    'in'                   => 'The :attribute must be one of the following types: :values',
    'url'                  => 'The :attribute is not a valid URL.',
    'array'                => 'The :attribute must be an array.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],

    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'regex'                => 'The :attribute format is invalid.',
    'string'               => 'The :attribute must be a string.',
    'attributes' => [
        '_id' => 'ID',
        'status' => 'Status',
        'url' => 'URL Address',
        'port' => 'Port',
        'method' => 'Method',
        'values' => 'Values',
        'keywords' => 'Keywords',
        'notify_type' => 'Notification type',
        'mobiles' => 'Mobiles',
        'emails' => 'Emails',
        'interval' => 'Interval',
        'timeout' => 'Timeout',
    ],
];
