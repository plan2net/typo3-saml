<?php
declare(strict_types=1);

$EM_CONF["cvc_saml"] = [
    'title' => 'SAML Authentication',
    'description' => 'This extension adds an authentication with SAML',
    'version' => '1.0.0',
    'category' => 'services',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
        ],
    ],
    'state' => 'beta',
    'author' => 'CARL von CHIARI GmbH',
    'author_email' => 'opensource@cvc.digital',
];
