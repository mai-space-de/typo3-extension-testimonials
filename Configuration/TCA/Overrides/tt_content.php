<?php

declare(strict_types=1);

defined('TYPO3') or die();

use Maispace\MaiBase\TableConfigurationArray\CType;
use Maispace\MaiBase\TableConfigurationArray\Helper;

$lang = Helper::localLangHelperFactory('mai_testimonials', 'Default/locallang_tca.xlf');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiTestimonials',
    'List',
    $lang('plugin.list.title'),
    'maispace_testimonials_list',
    'maispace_feature',
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiTestimonials',
    'Slider',
    $lang('plugin.slider.title'),
    'maispace_testimonials_slider',
    'maispace_feature',
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'MaiTestimonials',
    'Single',
    $lang('plugin.single.title'),
    'maispace_testimonials_single',
    'maispace_feature',
);

(new CType('maispace_testimonials_list', $lang('ctype.testimonials_list'), 'maispace_testimonials_list'))
    ->addDefaultHeaderPalette()
    ->addCustomFields('pi_flexform')
    ->addDefaultLanguageTab()
    ->addDefaultAccessTab()
    ->setGroup('maispace_feature')
    ->register();

(new CType('maispace_testimonials_slider', $lang('ctype.testimonials_slider'), 'maispace_testimonials_slider'))
    ->addDefaultHeaderPalette()
    ->addCustomFields('pi_flexform')
    ->addDefaultLanguageTab()
    ->addDefaultAccessTab()
    ->setGroup('maispace_feature')
    ->register();

(new CType('maispace_testimonials_single', $lang('ctype.testimonials_single'), 'maispace_testimonials_single'))
    ->addDefaultHeaderPalette()
    ->addCustomFields('pi_flexform')
    ->addDefaultLanguageTab()
    ->addDefaultAccessTab()
    ->setGroup('maispace_feature')
    ->register();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:mai_testimonials/Configuration/FlexForms/TestimonialsPlugin.xml',
    'maispace_testimonials_list',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:mai_testimonials/Configuration/FlexForms/TestimonialsPlugin.xml',
    'maispace_testimonials_slider',
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:mai_testimonials/Configuration/FlexForms/TestimonialsPlugin.xml',
    'maispace_testimonials_single',
);
