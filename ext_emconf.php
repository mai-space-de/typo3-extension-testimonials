<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Mai Testimonials',
    'description' => 'Testimonial records with author name, quote, role, image, and optional star rating. Categories use TYPO3 sys_category.',
    'category' => 'module',
    'author' => 'Maispace',
    'author_email' => '',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
