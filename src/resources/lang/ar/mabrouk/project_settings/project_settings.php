<?php

return [
    'update' => 'تم تحديث الإعدادت بنجاح',

    'attributes' => [
        'section' => 'قطاع إعدادات المشروع',
        'name' => 'اسم إعدادات المشروع',
        'description' => 'وصف إعدادات المشروع',
        'custom_validation_rules' => 'قواعد التحقق المخصصة لإعدادات المشروع',
        'editable' => 'قابل للتعديل',
        'return_to_client' => 'إرجاع إلى العميل',
        'phone' => [
            'phone' => 'الهاتف',
            'number' => 'رقم الهاتف',
            'country_code' => 'رمز دولة الهاتف',
        ],
        'image' => 'الصورة',
        'value' => 'القيمة',
        'displayed' => 'عرض الاعدادات فى الموقع',
        'admin_has_display_control' => 'المسؤول لديه السيطرة على العرض',
    ],

    'errors' => [
        'displayed_not_allowed' => 'يمكنك تعديل هذا الحقل إذا كان للمسؤول صلاحية التحكم فيه',
    ],
];
