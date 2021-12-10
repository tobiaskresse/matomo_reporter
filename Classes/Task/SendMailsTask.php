<?php
namespace Slub\MatomoReporter\Task;
//use Slub\MatomoReporter\Controller\SubscriberController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Core\Utility\MathUtility;
use Slub\MatomoReporter\Classes\Controller\SubscriberController;
use Slub\MatomoReporter\Domain\Repository\SubscriberRepository;
use Slub\MatomoReporter\Domain\Repository\CollectionsRepository;
use Slub\MatomoReporter\Domain\Repository\WebsitesRepository;


use TYPO3\CMS\Core\Utility\DebugUtility;
class SendMailsTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
           

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
    protected $collectionRepository = null;

    /**
     * websitesRepository
     * 
     * @var \Slub\MatomoReporter\Domain\Repository\WebsitesRepository
     */
    protected $websiteRepository = null;

    /**
     * initializeAction
     *
     * @return void
     */
    protected function initializeAction()
    {

        $objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

        $this->subscriberRepository = $objectManager->get(
            \Slub\MatomoReporter\Domain\Repository\SubscriberRepository::class
        );

        $this->collectionRepository = $objectManager->get(
            \Slub\MatomoReporter\Domain\Repository\CollectionsRepository::class
        );

        $this->websiteRepository = $objectManager->get(
            \Slub\MatomoReporter\Domain\Repository\WebsitesRepository::class
        );

        $this->configurationManager = $objectManager->get(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::class
        );

        $this->persistenceManager = $objectManager->get(
            \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class
        );

        switch ($this->language) {
            case 'de':  setlocale(LC_ALL, 'de_DE.utf8');
                        $GLOBALS['LANG']->init('de');
                        break;
            case 'en':
            default:
                        setlocale(LC_ALL, 'en_US.utf8');
                        $GLOBALS['LANG']->init('en');
                        break;
        }
    }
    
    public function execute() {
        $this->initializeAction();
        
        //$successfullyExecuted = false;
        if (MathUtility::canBeInterpretedAsInteger($this->storagePid)) {
            //$successfullyExecuted = true;
            
            // set storagePid to point extbase to the right repositories
            $configurationArray = [
                'persistence' => [
                    'storagePid' => $this->storagePid,
                ],
            ];
            
            $this->configurationManager->setConfiguration($configurationArray);
        }else 
        {
            //DebugUtility::debug($configurationArray);
            //return $successfullyExecuted;
            $configurationArray = [
                'persistence' => [
                    'storagePid' => 7,
                ],
            ];
            //$this->configurationManager->setConfiguration($configurationArray);
        }

            //SubscriberController::sendMailsAction();
            $subscribers = $this->subscriberRepository->findAll();
            //DebugUtility::debug($subscribers);
            foreach ($subscribers as $subscriber) {
                $collectionNames = "";
                $collectionNamesHtml = "";
                $websiteNames = "";
                $websiteNamesHtml = "";
                foreach ($subscriber->getCollections() as $item) {
                    //DebugUtility::debug($item);
                    if ($collectionNames == "") {
                        $collectionNames = $collectionNames . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                        $collectionNamesHtml = $collectionNamesHtml . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    } else {
                        $collectionNames = $collectionNames . ",\n" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                        $collectionNamesHtml = $collectionNamesHtml . ",</br>" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    }
                }
                foreach ($subscriber->getWebsites() as $item) {
                    DebugUtility::debug($item);
                    if ($websiteNames == "") {
                        $websiteNames = $websiteNames . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                        $websiteNamesHtml = $websiteNamesHtml . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    } else {
                        $websiteNames = $websiteNames . ",\n" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                        $websiteNamesHtml = $websiteNamesHtml . ",</br>" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    }
                }
            
                // Create the message
                $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
            
                // Prepare and send the message
                $mail->setSubject('Monthly Report')->setFrom(array('Tobias.kresse@slub-dresden.de' => 'John Doe'))->setTo(array($subscriber->getEmail() => $subscriber->getName()))->setBody("Collections: \n" . $collectionsNames . "\n\nWebsites:\n" . $websiteNames)->addPart("<p>Collections: </br> {$collectionNamesHtml} </p><p>Websites:</br> {$websiteNamesHtml} </p>", 'text/html')->send();
                $successfullyExecuted = true;
            }
            //$this->redirect('list');


            return $successfullyExecuted;
    }
        


    
    
}      