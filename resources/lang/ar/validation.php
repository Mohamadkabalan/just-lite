<?php

// Only the rules the public forms use (app/Http/Requests). The fallback locale
// is also "ar", so any rule missing here shows its raw key, e.g. "validation.url".
return [
    'date' => 'حقل :attribute ليس تاريخًا صالحًا.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالحًا.',
    'max' => [
        'string' => 'يجب ألا يزيد :attribute عن :max حرفًا.',
    ],
    'required' => 'حقل :attribute مطلوب.',
    'string' => 'يجب أن يكون :attribute نصًا.',

    'attributes' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'phone' => 'رقم الهاتف',
        'phone_number' => 'رقم الهاتف',
        'msg_subject' => 'الموضوع',
        'message' => 'الرسالة',
        'appointment_date' => 'التاريخ',
        'type_of_service' => 'نوع الخدمة',
    ],
];
