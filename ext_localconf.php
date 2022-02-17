<?php

/*
 * Twig extension for TYPO3 CMS
 * Copyright (C) 2022 CARL von CHIARI GmbH
 *
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
    'Cvc.CvcSaml',
    'auth',
    Cvc\Typo3\CvcSaml\Service\SamlAuthenticationService::class,
    [
        'title' => 'Azure AD Credential Service',
        'description' => 'Manages login with Azure AD Credentials',
        'subtype' => 'authUserBE, getUserBE',
        'available' => true,
        'priority' => 100,
        'quality' => 100,
        'os' => '',
        'exec' => '',
        'className' => Cvc\Typo3\CvcSaml\Service\SamlAuthenticationService::class,
    ]
);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['loginProviders'][1569568897] = [
    'provider' => \Cvc\Typo3\CvcSaml\LoginProvider\SamlLoginProvider::class,
    'sorting' => 50,
    'icon-class' => 'fa-key',
    'label' => 'LLL:EXT:cvc_saml/Resources/Private/Language/locallang.xlf:login.label',
];


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['logoff_post_processing'][] = \Cvc\Typo3\CvcSaml\Hooks\BackendUserLogout::class . '->postProcess';
