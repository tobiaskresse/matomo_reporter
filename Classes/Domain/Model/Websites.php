<?php
namespace Slub\MatomoReporter\Domain\Model;


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
/**
 * Websites
 */
class Websites extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     * 
     * @var string
     */
    protected $name = '';

    /**
     * visits
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\MatomoReporter\Domain\Model\Visits>
     */
    protected $visits = null;

    /**
     * Returns the name
     * 
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     * 
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * __construct
     */
    public function __construct()
    {

        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     * 
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->visits = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a Visits
     * 
     * @param \Slub\MatomoReporter\Domain\Model\Visits $visit
     * @return void
     */
    public function addVisit(\Slub\MatomoReporter\Domain\Model\Visits $visit)
    {
        $this->visits->attach($visit);
    }

    /**
     * Removes a Visits
     * 
     * @param \Slub\MatomoReporter\Domain\Model\Visits $visitToRemove The Visits to be removed
     * @return void
     */
    public function removeVisit(\Slub\MatomoReporter\Domain\Model\Visits $visitToRemove)
    {
        $this->visits->detach($visitToRemove);
    }

    /**
     * Returns the visits
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\MatomoReporter\Domain\Model\Visits> $visits
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Sets the visits
     * 
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\MatomoReporter\Domain\Model\Visits> $visits
     * @return void
     */
    public function setVisits(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $visits)
    {
        $this->visits = $visits;
    }
}
