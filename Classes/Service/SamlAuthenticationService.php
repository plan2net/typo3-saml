<?php

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

namespace Cvc\Typo3\CvcSaml\Service;

use TYPO3\CMS\Beuser\Domain\Model\BackendUser;
use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Repository\BackendUserRepository;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class SamlAuthenticationService extends AbstractAuthenticationService
{
    private BackendUserRepository $backendUserRepository;

    private array $configuration;

    private string $authSource;

    private PasswordHashFactory $hashFactory;

    public function __construct(ExtensionConfiguration $configuration = null, BackendUserRepository $backendUserRepository = null, PasswordHashFactory $hashFactory = null)
    {
        $this->backendUserRepository = $backendUserRepository ?? GeneralUtility::makeInstance(ObjectManager::class)->get(BackendUserRepository::class);
        $this->configuration = $configuration ?? GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('cvc_saml');
        $this->authSource = !empty($this->configuration['authSource']) ? $this->configuration['authSource'] : 'default-sp';
        $this->hashFactory = $backendUserRepository ?? GeneralUtility::makeInstance(ObjectManager::class)->get(PasswordHashFactory::class);
    }

    public function authUser(array $user): int
    {
        if (!$this->isResponsible()) {
            return 100;
        }

        $as = new \SimpleSAML\Auth\Simple($this->authSource);
        if (!$as->isAuthenticated()) {
            $as->requireAuth();
        }

        return $as->isAuthenticated() ? 200 : 0;
    }

    public function getUser()
    {
        if (!$this->isResponsible()) {
            return false;
        }

        $as = new \SimpleSAML\Auth\Simple($this->authSource);

        if (!$as->isAuthenticated()) {
            $as->requireAuth();
        }
        $data = [
            'email' => $as->getAttributes()['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'][0],
            'givenname' => $as->getAttributes()['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'][0],
            'surname' => $as->getAttributes()['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname'][0],
        ];
        /** @var BackendUser $backendUser */
        $backendUser = $this->backendUserRepository->findByEmail($data['email'])[0];
        if ($backendUser === null) {
            if (!$this->createBackendUser($data)) {
                return false;
            }
            $backendUser = $this->backendUserRepository->findByEmail($data['email'])[0];
        }

        return $this->fetchUserRecord($backendUser->getUserName());
    }

    private function createBackendUser($data): bool
    {
        if (!GeneralUtility::validEmail($data['email'])) {
            return false;
        }

        $hashInstance = $this->hashFactory->getDefaultHashInstance('BE');
        $hashedPassword = $hashInstance->getHashedPassword($this->generateRandomString());
        $backenduserFields = [
            'realName' => $data['givenname'].' '.$data['surname'],
            'password' => $hashedPassword,
            'admin' => 0,
            'tstamp' => $GLOBALS['EXEC_TIME'],
            'crdate' => $GLOBALS['EXEC_TIME'],
        ];

        $backenduserFields['email'] = $data['email'];
        $backenduserFields['username'] = $data['email'];

        if (($this->configuration['defaultBeGroup'] !== '')) {
            $backenduserFields['usergroup'] = $this->configuration['defaultBeGroup'];
        }
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $connectionPool->getConnectionForTable('be_users')->insert('be_users', $backenduserFields);

        return true;
    }

    private function generateRandomString($length = 10)
    {
        return mb_substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / mb_strlen($x)))), 1, $length);
    }

    private function isResponsible(): bool
    {
        if (GeneralUtility::_GP('loginProvider') === '1569568897') {
            return true;
        }

        return false;
    }
}
