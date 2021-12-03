<?php
namespace Slub\MatomoReporter\Task;
use Slub\MatomoReporter\Controller\SubscriberController;
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
    
    public function execute() {
            injectSubscriberRepository();
            injectCollectionsRepository();
            injectWebsitesRepository();

            //SubscriberController::sendMailsAction();
            $subscribers = $this->subscriberRepository->findAll();
            foreach ($subscribers as $subscriber) {
                $collectionNames = "";
                $collectionNamesHtml = "";
                $websiteNames = "";
                $websiteNamesHtml = "";
                foreach ($subscriber->getCollections() as $item) {
                    if ($collectionNames == "") {
                        $collectionNames = $collectionNames . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                        $collectionNamesHtml = $collectionNamesHtml . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    } else {
                        $collectionNames = $collectionNames . ",\n" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                        $collectionNamesHtml = $collectionNamesHtml . ",</br>" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    }
                }
                foreach ($subscriber->getWebsites() as $item) {
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
            }
            //$this->redirect('list');
        }
        
    
}      