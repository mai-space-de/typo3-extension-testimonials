<?php

declare(strict_types=1);

use Maispace\MaiBase\Icon\StreamlineSvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$allIcons = 'EXT:mai_base/Resources/Public/Icons/all-icons/';
$quote = $allIcons . 'interface-text-formatting-quotation-1--quote-quotation-format-formatting-open-close-marks-text--Streamline-Core.svg';

return [
    'ext-maispace-mai_testimonials' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:mai_testimonials/Resources/Public/Icons/Extension.svg',
    ],
    'mai-testimonials' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $quote,
    ],
    'tx_maitestimonials_testimonial' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $quote,
    ],
    'maispace_testimonials_slider' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $quote,
    ],
    'maispace_testimonials_list' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $quote,
    ],
    'maispace_testimonials_single' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $quote,
    ],
];
