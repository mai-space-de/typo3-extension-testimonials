<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Mai Testimonials',
    'description' => 'Testimonials extension with slider, grid, and single-quote layouts. Records store quote text, author name, role, organisation, portrait image (FAL), and sys_category. Supports category filtering and a Story Wall for community testimonials.',
    'category' => 'module',
    'author' => 'Maispace',
    'author_email' => '',
    'state' => 'alpha',
    'version' => '0.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
