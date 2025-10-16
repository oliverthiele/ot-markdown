<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

call_user_func(static function () {
    $key = 'ot_markdown';

    // Neues Feld für Inline/File-Auswahl hinzufügen
    $temporaryColumns = [
        'tx_otmarkdown_mode' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $key . '/Resources/Private/Language/locallang_db.xlf:tt_content.tx_otmarkdown_mode',
            'config' => [
                'type' => 'radio',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:ot_markdown/Resources/Private/Language/locallang_db.xlf:tt_content.tx_otmarkdown_mode.inline',
                        'value' => 'inline',
                    ],
                    [
                        'label' => 'LLL:EXT:ot_markdown/Resources/Private/Language/locallang_db.xlf:tt_content.tx_otmarkdown_mode.file',
                        'value' => 'file',
                    ],
                ],
                'default' => 'inline',
            ],
            'onChange' => 'reload',
        ],
    ];

    ExtensionManagementUtility::addTCAcolumns('tt_content', $temporaryColumns);

    // Register CType ot_markdown
    ExtensionManagementUtility::addTcaSelectItem(
        'tt_content',
        'CType',
        [
            'label' => 'LLL:EXT:' . $key . '/Resources/Private/Language/locallang_db.xlf:tt_content.CType.ot_markdown',
            'description' => 'LLL:EXT:' . $key . '/Resources/Private/Language/locallang_db.xlf:tt_content.CType.ot_markdown.description',
            'value' => $key,
            'icon' => 'ot-markdown',
            'group' => 'extras',
        ],
        'textmedia',
        'after'
    );

    $GLOBALS['TCA']['tt_content']['types'][$key] = [
        'showitem' => '
            --palette--;;general,
            --palette--;;headers,
            tx_otmarkdown_mode,
            bodytext,
            assets,
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access
        ',
        'columnsOverrides' => [
            'bodytext' => [
                'label' => 'LLL:EXT:ot_markdown/Resources/Private/Language/locallang_db.xlf:tt_content.bodytext',
                'displayCond' => 'FIELD:tx_otmarkdown_mode:=:inline',
                'config' => [
                    'renderType' => 'codeEditor',
                    'rows' => 20,
                    'cols' => 80,
                    'eval' => 'trim',
                ],
            ],
            'assets' => [
                'label' => 'LLL:EXT:ot_markdown/Resources/Private/Language/locallang_db.xlf:tt_content.assets',
                'config' => [
                    'type' => 'file',
                    'maxitems' => 1,
                    'allowed' => 'md,markdown,txt',
                    'appearance' => [
                        'createNewRelationLinkTitle' =>
                            'LLL:EXT:ot_markdown/Resources/Private/Language/locallang_db.xlf:tt_content.assets.addFile',
                    ],
                    'behaviour' => [
                        'allowLanguageSynchronization' => true,
                    ],
                ],
                'displayCond' => 'FIELD:tx_otmarkdown_mode:=:file',
            ],
        ],
    ];
});
