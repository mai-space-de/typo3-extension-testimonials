<?php

declare(strict_types=1);

defined('TYPO3') or die();

use Maispace\MaiBase\TableConfigurationArray\Helper;

$lang = Helper::localLangHelperFactory('mai_testimonials', 'Default/locallang_tca.xlf');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiTestimonials',
    'List',
    $lang('plugin.list.title'),
    'mai-content',
    'maispace_plugins_lists',
    '',
    'FILE:EXT:mai_testimonials/Configuration/FlexForms/TestimonialsPlugin.xml',
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiTestimonials',
    'Slider',
    $lang('plugin.slider.title'),
    'mai-content',
    'maispace_plugins_lists',
    '',
    'FILE:EXT:mai_testimonials/Configuration/FlexForms/TestimonialsPlugin.xml',
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiTestimonials',
    'Single',
    $lang('plugin.single.title'),
    'mai-content',
    'maispace_plugins_lists',
    '',
    'FILE:EXT:mai_testimonials/Configuration/FlexForms/TestimonialsPlugin.xml',
);
