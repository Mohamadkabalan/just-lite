<?php

// Only the rules the public forms and the admin forms use (app/Http/Requests,
// Voyager BREAD rules, user passwords). The fallback locale is also "ar", so
// any rule missing here shows its raw key, e.g. "validation.url".
return [
    'after_or_equal' => 'يجب أن يكون :attribute تاريخًا لاحقًا أو مساويًا لـ :date.',
    'date' => 'حقل :attribute ليس تاريخًا صالحًا.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالحًا.',
    'max' => [
        'string' => 'يجب ألا يزيد :attribute عن :max حرفًا.',
    ],
    'min' => [
        'string' => 'يجب ألا يقل :attribute عن :min أحرف.',
    ],
    'numeric' => 'يجب أن يكون :attribute رقمًا.',
    'prohibited' => 'يجب أن يبقى حقل :attribute فارغًا.',
    'regex' => 'صيغة :attribute غير صالحة.',
    'required' => 'حقل :attribute مطلوب.',
    'string' => 'يجب أن يكون :attribute نصًا.',

    'custom' => [
        'phone' => ['regex' => 'يرجى إدخال رقم هاتف صالح.'],
        'phone_number' => ['regex' => 'يرجى إدخال رقم هاتف صالح.'],
        'appointment_date' => ['after_or_equal' => 'يرجى اختيار تاريخ اليوم أو تاريخ لاحق.'],
        'form_started' => ['required' => 'انتهت صلاحية هذا النموذج. يرجى إعادة تحميل الصفحة والمحاولة مرة أخرى.'],
        'website' => ['prohibited' => 'يرجى ترك حقل الموقع فارغًا.'],
    ],

    'attributes' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'phone_number' => 'رقم الهاتف',
        'msg_subject' => 'الموضوع',
        'message' => 'الرسالة',
        'appointment_date' => 'التاريخ',
        'type_of_service' => 'نوع الخدمة',
        'password' => 'كلمة المرور',
        'website' => 'الموقع',
    ],
];
