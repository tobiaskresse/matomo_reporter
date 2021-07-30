<?php
namespace Slub\MatomoReporter\Controller;


/***
 *
 * This file is part of the "SLUB Matomo Reporter" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Alexander Bigga <typo3@slub-dresden.de>, SLUB Dresden
 *
 ***/

use  Slub\MatomoReporter\Domain\Repository\SubscriberRepository;
use  Slub\MatomoReporter\Domain\Repository\CollectionsRepository;
use  Slub\MatomoReporter\Domain\Repository\WebsitesRepository;

/**
 * AbstractController
 */
class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * subscriberRepository
     * 
     * @var \Slub\MatomoReporter\Domain\Repository\SubscriberRepository
     */
    protected $subscriberRepository = null;

    /**
     * collectionsRepository
     * 
     * @var \Slub\MatomoReporter\Domain\Repository\CollectionsRepository
     */
    protected $collectionsRepository = null;

    /**
     * websitesRepository
     * 
     * @var \Slub\MatomoReporter\Domain\Repository\WebsitesRepository
     */
    protected $websitesRepository = null;

    /**
     * @param \Slub\MatomoReporter\Domain\Repository\SubscriberRepository $subscriberRepository
    */
    public function injectSubscriberRepository(SubscriberRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * @param \Slub\MatomoReporter\Domain\Repository\CollectionsRepository $collectionsRepository
    */
    public function injectCollectionsRepository(CollectionsRepository $collectionsRepository)
    {
        $this->collectionsRepository = $collectionsRepository;
    }

    /**
     * @param \Slub\MatomoReporter\Domain\Repository\WebsitesRepository $websitesRepository
    */
    public function injectWebsitesRepository(SubscriberRepository $websitesRepository)
    {
        $this->websitesRepository = $websitesRepository;
    }

}