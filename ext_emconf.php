<?php

$EM_CONF['ot_markdown'] = [
    'title' => 'CE Markdown',
    'description' => 'Content Element and ViewHelper for Markdown with optional syntax highlighting.',
    'category' => 'frontend',
    'author' => 'Oliver Thiele',
    'author_email' => 'mail@oliver-thiele.de',
    'state' => 'stable',
    'author_company' => 'Web Development Oliver Thiele',
    'version' => '1.0.5',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '13.4.0-13.4.99',
                    'php' => '8.2.0-8.99.99',
                ],
            'conflicts' =>
                [],
            'suggests' =>
                [],
        ],
];
