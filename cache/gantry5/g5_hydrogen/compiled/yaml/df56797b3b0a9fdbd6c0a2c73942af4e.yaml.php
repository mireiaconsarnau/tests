<?php
return [
    '@class' => 'Gantry\\Component\\File\\CompiledYamlFile',
    'filename' => '/var/www/html/webasix/templates/g5_hydrogen/blueprints/styles/accent.yaml',
    'modified' => 1500890567,
    'data' => [
        'name' => 'Accent Colors',
        'description' => 'Accent colors for the Hydrogen theme',
        'type' => 'core',
        'form' => [
            'fields' => [
                'color-1' => [
                    'type' => 'input.colorpicker',
                    'label' => 'Accent Color 1',
                    'default' => '#439A86'
                ],
                'color-2' => [
                    'type' => 'input.colorpicker',
                    'label' => 'Accent Color 2',
                    'default' => '#8F4DAE'
                ]
            ]
        ]
    ]
];
