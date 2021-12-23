<?php

/*
|--------------------------------------------------------------------------
| Custom Validation Language Lines
|--------------------------------------------------------------------------
|
| Here you may specify custom validation messages for attributes using the
| convention 'attribute.rule' to name the lines. This makes it quick to
| specify a specific custom language line for a given attribute rule.
|
*/

return [
    'required'             => 'فیلد :attribute الزامی است',
    'exists'               => ':attribute انتخاب شده، معتبر نیست.',
    'unique'               => 'این :attribute قبلا انتخاب شده است.',
    'in'                   => ':attribute باید یکی از این مقادیر باشد (:values)',
    'url'                  => 'آدرس :attribute معتبر نیست',
    'array'                => ':attribute باید شامل آرایه باشد.',
    'between' => array(
        'numeric'          => ':attribute باید بین :min و :max باشد.',
        'file'             => ':attribute باید بین :min و :max کیلوبایت باشد.',
        'string'           => ':attribute باید بین :min و :max کاراکتر باشد.',
        'array'            => ':attribute باید بین :min و :max آیتم باشد.',
    ),
    'integer'              => ':attribute باید نوع داده ای عددی باشد.',
    'ip'                   => ':attribute باید IP آدرس معتبر باشد.',
    'max' => array(
        'numeric'          => ':attribute نباید بزرگتر از :max باشد.',
        'file'             => ':attribute نباید بزرگتر از :max کیلوبایت باشد.',
        'string'           => ':attribute نباید بیشتر از :max کاراکتر باشد.',
        'array'            => ':attribute نباید بیشتر از :max آیتم باشد.',
    ),
    'min' => array(
        'numeric'          => ':attribute نباید کوچکتر از :min باشد.',
        'file'             => ':attribute نباید کوچکتر از :min کیلوبایت باشد.',
        'string'           => ':attribute نباید کمتر از :min کاراکتر باشد.',
        'array'            => ':attribute نباید کمتر از :min آیتم باشد.',
    ),
    'regex'                => 'فرمت :attribute معتبر نیست.',
    'string'               => ':attribute باید نوع داده ای متنی باشد.',
    'attributes'           => [
        '_id'              => 'کد',
        'status'           => 'وضعیت',
        'url'              => 'آدرس',
        'port'             => 'پورت',
        'method'           => 'متد',
        'values'           => 'مقادیر',
        'keywords'         => 'کلمات کلیدی',
        'notify_type'      => 'نحوه اطلاع رسانی',
        'mobiles'          => 'موبایل ها',
        'emails'           => 'ایمیل ها',
        'interval'         => 'فاصله بین دو درخواست',
        'timeout'          => 'مدت زمان انتظار',
        'mobile'           => 'شماره موبایل',
        'name'             => 'نام',
        'family'           => 'فامیل',
    ],
];
