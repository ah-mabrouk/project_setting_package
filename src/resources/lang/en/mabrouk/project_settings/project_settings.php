<?php

return [
    'update' => 'Project setting updated successfully',

    'attributes' => [
        'section' => 'project setting section',
        'name' => 'project setting name',
        'description' => 'project setting description',
        'custom_validation_rules' => 'project setting custom validation rules',
        'editable' => 'editable',
        'return_to_client' => 'return to client',
        'phone' => [
            'phone' => 'phone',
            'number' => 'phone number',
            'country_code' => 'phone country code',
        ],
        'image' => 'image',
        'value' => 'value',
        'displayed' => 'display item in website',
        'admin_has_display_control' => 'admin has display control',
    ],

    'errors' => [
        'displayed_not_allowed' => 'You can edit this field if the admin have control over it',
    ],    
];
