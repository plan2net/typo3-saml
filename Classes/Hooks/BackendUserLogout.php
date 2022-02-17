<?php

declare(strict_types=1);

/*
 * SAML extension for TYPO3 CMS
 * Copyright (C) 2022 CARL von CHIARI GmbH
 *
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 3
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Cvc\Typo3\CvcSaml\Hooks;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendUserLogout
{
    public function postProcess($_params, $ref)
    {
        if ($ref instanceof BackendUserAuthentication && $ref->newSessionID === false) {
            $configuration ?? GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cvc_saml');
            $as = new \SimpleSAML\Auth\Simple(!empty($this->configuration['authSource']) ? $this->configuration['authSource'] : 'default-sp');
            $as->logout();
        }
    }
}
