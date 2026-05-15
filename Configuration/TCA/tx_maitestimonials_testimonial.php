<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\CategoryConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\FileConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\TextConfig;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_testimonials', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_maitestimonials_testimonial')))
    ->setSearchFields('quote,author_name,organisation')
    ->setDefaultConfig()
    ->setLabel('author_name')
    ->setIconFile('EXT:mai_base/Resources/Public/Icons/generic_table.svg')
    ->setSortingField()
    ->addColumn(
        'quote',
        $lang('tx_maitestimonials_testimonial.quote'),
        (new TextConfig())->setRows(5)->setCols(50)->setRequired()
    )
    ->addColumn(
        'author_name',
        $lang('tx_maitestimonials_testimonial.author_name'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')->setRequired()
    )
    ->addColumn(
        'author_role',
        $lang('tx_maitestimonials_testimonial.author_role'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')
    )
    ->addColumn(
        'organisation',
        $lang('tx_maitestimonials_testimonial.organisation'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')
    )
    ->addColumn(
        'portrait',
        $lang('tx_maitestimonials_testimonial.portrait'),
        (new FileConfig())->setMaxItems(1)->setAllowed('jpg,jpeg,png,webp,svg')
    )
    ->addColumn(
        'categories',
        $lang('tx_maitestimonials_testimonial.categories'),
        new CategoryConfig()
    )
    ->addTypeShowItem(
        '0',
        'quote, author_name, author_role, organisation, portrait, categories,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
