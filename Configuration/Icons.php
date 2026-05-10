<?php

declare(strict_types=1);

return [
    'ext-maispace-mai_testimonials' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:mai_testimonials/Resources/Public/Icons/Extension.svg',
    ],
    'tx_maitestimonials_testimonial' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:mai_testimonials/Resources/Public/Icons/tx_maitestimonials_testimonial.svg',
    ],
    'maispace_testimonials_slider' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:mai_testimonials/Resources/Public/Icons/ContentElement/TestimonialsSlider.svg',
    ],
    'maispace_testimonials_list' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:mai_testimonials/Resources/Public/Icons/ContentElement/TestimonialsGrid.svg',
    ],
    'maispace_testimonials_single' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:mai_testimonials/Resources/Public/Icons/ContentElement/TestimonialsSingle.svg',
    ],
];
