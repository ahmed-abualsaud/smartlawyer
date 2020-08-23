<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'ال :attribute يجب قبول .',
    'active_url' => 'ال :attribute غير صحيح URL.',
    'after' => 'ال :attribute يجب أن يكون تاريخ بعد :date.',
    'after_or_equal' => 'ال :attribute يجب أن يكون تاريخ بعد أو يساوي :date.',
    'alpha' => 'ال :attribute البيان قد يحتوي على أحرف فقط.',
    'alpha_dash' => 'ال :attribute البيان قد يحتوي فقط على أحرف وأرقام واشرط واشرط سفلية.',
    'alpha_num' => 'ال :attribute البيان قد يحتوي فقط على أحرف وأرقام.',
    'array' => 'ال :attribute يجب أن يكون مصفوفة.',
    'before' => 'ال :attribute يجب أن يكون تاريخ من قبل :date.',
    'before_or_equal' => 'ال :attribute يجب أن يكون تاريخًا قبل أو يساوي :date.',
    'between' => [
        'numeric' => 'ال :attribute يجب ان يكون ما بين :min و :max.',
        'file' => 'ال :attribute يجب ان يكون ما بين :min و :max الكيلوبايت.',
        'string' => 'ال :attribute يجب ان يكون ما بين :min و :max الحروف.',
        'array' => 'ال :attribute يجب ان يكون ما بين :min و :max العناصر.',
    ],
    'boolean' => 'ال :attribute يجب أن يكون البيان صحيحًا أو خطأ.',
    'confirmed' => 'ال :attribute التأكيد غير متطابق.',
    'date' => 'ال :attribute هذا ليس تاريخ صحيح.',
    'date_equals' => 'ال :attribute يجب أن يكون التاريخ يساوي :date.',
    'date_format' => 'ال :attribute لا يطابق التنسيق :format.',
    'different' => 'ال :attribute و :other يجب أن تكون مختلف.',
    'digits' => 'ال :attribute يجب يكون :digits أرقام.',
    'digits_between' => 'ال :attribute يجب ان تكون :min و :max ارقام.',
    'dimensions' => 'ال :attribute أبعاد الصورة غير صالحة.',
    'distinct' => 'ال :attribute البيان له قيمة مكررة.',
    'email' => 'ال :attribute يجب أن يكون عنوان بريد إلكتروني صالح.',
    'ends_with' => 'ال :attribute يجب أن تنتهي بواحده من following: :values',
    'exists' => 'المختار :attribute يكون غير صحيح.',
    'file' => 'ال :attribute يجب أن يكون ملف.',
    'filled' => 'ال :attribute يجب أن يكون للبيان قيمة.',
    'gt' => [
        'numeric' => 'ال :attribute يجب ان تكون اكبر من :value.',
        'file' => 'ال :attribute يجب ان تكون اكبر من :value kilobytes.',
        'string' => 'ال :attribute يجب ان تكون اكبر من :value characters.',
        'array' => 'ال :attribute يجب أن يكون لديك أكثر من :value العناصر.',
    ],
    'gte' => [
        'numeric' => 'ال :attribute يجب ان تكون اكبر من او يساوي :value.',
        'file' => 'ال :attribute يجب ان تكون اكبر من او يساوي :value kilobytes.',
        'string' => 'ال :attribute يجب ان تكون اكبر من او يساوي :value characters.',
        'array' => 'ال :attribute يجب ان تحتوي :value عناصر او اكثر.',
    ],
    'image' => 'ال :attribute يجب ان تكون صوره.',
    'in' => 'المختار :attribute تكون غير صحيحه.',
    'in_array' => 'ال :attribute البيان غير موجود في :other.',
    'integer' => 'ال :attribute يجب ان يكون رقم صحيح.',
    'ip' => 'ال :attribute يجب أن يكون عنوان IP صحيح.',
    'ipv4' => 'ال :attribute يجب أن يكون عنوان IPv4 صحيح.',
    'ipv6' => 'ال :attribute يجب أن يكون عنوان IPv6 صحيح.',
    'json' => 'ال :attribute يجب أن تكون سلسلة JSON صحيح',
    'lt' => [
        'numeric' => 'ال :attribute يجب ان يكون اقل من :value.',
        'file' => 'ال :attribute يجب ان يكون اقل من :value كيلوبايت.',
        'string' => 'ال :attribute يجب ان يكون اقل من :value حروف.',
        'array' => 'ال :attribute يجب ان يكون اقل من :value عناصر.',
    ],
    'lte' => [
        'numeric' => 'ال :attribute يجب ان يكون اقل من او يساوي :value.',
        'file' => 'ال :attribute يجب ان يكون اقل من او يساوي :value كيلوبايت.',
        'string' => 'ال :attribute يجب ان يكون اقل من او يساوي :value حروف.',
        'array' => 'ال :attribute يجب ان لا تحتوي على اكثر من :value عناصر.',
    ],
    'max' => [
        'numeric' => 'ال :attribute قد لا يكون أكبر من :max.',
        'file' => 'ال :attribute قد لا يكون أكبر من :max كيلوبايت.',
        'string' => 'ال :attribute قد لا يكون أكبر من :max حروف.',
        'array' => 'ال :attribute قد لا يكون أكبر من :max عناصر.',
    ],
    'mimes' => 'ال :attribute يجب أن يكون ملف type: :values.',
    'mimetypes' => 'ال :attribute يجب أن يكون ملف type: :values.',
    'min' => [
        'numeric' => 'ال :attribute يجب أن يكون على الأقل :min.',
        'file' => 'ال :attribute يجب أن يكون على الأقل :min الكيلوبايت.',
        'string' => 'ال :attribute يجب أن يكون على الأقل :min الحروف.',
        'array' => 'ال :attribute يجب أن يكون على الأقل :min العناصر.',
    ],
    'not_in' => 'المختار :attribute يكون غير صحيح.',
    'not_regex' => 'ال :attribute التنسيق غير صحيح.',
    'numeric' => 'ال :attribute يجب ان يكون رقم.',
    'present' => 'ال :attribute يجب ان يكون موجود.',
    'regex' => 'ال :attribute التنسيق غير صحيح.',
    'required' => 'ال :attribute يكون مطلوب.',
    'required_if' => 'ال :attribute يكون مطلوب عندما :other تكون :value.',
    'required_unless' => 'ال :attribute مطلوب إلا :other ان تكون في :values.',
    'required_with' => 'ال :attribute مطلوب عندما :values تكون متاحه.',
    'required_with_all' => 'ال :attribute مطلوب عندما :values تكون متاحه.',
    'required_without' => 'ال :attribute مطلوب عندما :values تكون غير متاحه.',
    'required_without_all' => 'ال :attribute مطلوب عندما لا شيء من :values تكون متاحه.',
    'same' => 'ال :attribute و :other يجب أن تتطابق.',
    'size' => [
        'numeric' => 'ال :attribute يجب ان :size.',
        'file' => 'ال :attribute يجب ان :size كيلو بايت.',
        'string' => 'ال :attribute يجب ان :size الحروف.',
        'array' => 'ال :attribute يجب ان تحتوي :size العناصر.',
    ],
    'starts_with' => 'ال :attribute يجب أن تبدأ بأحد following: :values',
    'string' => 'ال :attribute يجب أن يكون كلمه.',
    'timezone' => 'ال :attribute يجب أن يكون نطاق صحيح.',
    'unique' => 'ال :attribute لقد تم بالفعل.',
    'uploaded' => 'ال :attribute فشل في التحميل.',
    'url' => 'ال :attribute التنسيق غير صحيح.',
    'uuid' => 'ال :attribute يجب أن يكون UUID صحيحا.',

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

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'رسالة مخصصة',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
