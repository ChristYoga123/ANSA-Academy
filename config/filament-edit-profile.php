<?php

return [
    'show_custom_fields' => true,
    'custom_fields' => [
        'alamat' => [
            'type' => 'text',
            'label' => 'Alamat',
            'placeholder' => 'Masukkan Alamat',
            'required' => true,
            'rules' => 'required|string|max:255',
        ],
        'no_hp' => [
            'type' => 'text',
            'label' => 'Nomor HP',
            'placeholder' => 'Masukkan Nomor HP (62xxxxxxx)',
            'required' => true,
            'rules' => 'required|numeric',
        ],
        'linkedin' => [
            'type' => 'text',
            'label' => 'Linkedin',
            'placeholder' => 'Masukkan Linkedin',
            'required' => true,
            'rules' => 'required|string|max:255',
        ],
        'instagram' => [
            'type' => 'text',
            'label' => 'Instagram',
            'placeholder' => 'Masukkan Instragram',
            'required' => true,
            'rules' => 'required|string|max:255',
        ],
    ],
];
