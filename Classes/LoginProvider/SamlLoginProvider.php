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

namespace Cvc\Typo3\CvcSaml\LoginProvider;

use TYPO3\CMS\Backend\Controller\LoginController;
use TYPO3\CMS\Backend\LoginProvider\LoginProviderInterface;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

class SamlLoginProvider implements LoginProviderInterface
{
    public function render(StandaloneView $view, PageRenderer $pageRenderer, LoginController $loginController)
    {
        $pageRenderer->addCssFile(GeneralUtility::getFileAbsFileName('EXT:cvc_saml/Resources/Public/Css/samllogin.css'), 'stylesheet', 'all', '', false, false, '', true, '', true);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:cvc_saml/Resources/Private/Templates/SamlLoginForm.html'));
    }
}
