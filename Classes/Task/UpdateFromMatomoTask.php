<?php
namespace Slub\MatomoReporter\Task;
//do with cron job if possible with ddev 
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Core\Utility\MathUtility;
use  Slub\MatomoReporter\Domain\Repository\SubscriberRepository;
use  Slub\MatomoReporter\Domain\Repository\CollectionsRepository;
use  Slub\MatomoReporter\Domain\Repository\WebsitesRepository;
class UpdateFromMatomoTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

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
        
        
        
        


    

        $SiteId = "412";//variable for the schduler?
        $date = new \DateTime();

        //var_dump($date);
        $token = "c4f74d28bff8907dca5445c7c0893c6e";
        $url = "https://matomo.slub-dresden.de/index.php?module=API&method=CustomVariables.getCustomVariablesValuesFromNameId&idSite=412&period=month&date=2021-05-05&idSubtable=1&format=JSON&token_auth=" . $token;

        // variable DateTime
        //$url = "https://matomo.slub-dresden.de/index.php?module=API&method=CustomVariables.getCustomVariablesValuesFromNameId&idSite=". $SiteId ."&period=month&date=" . $date->format('y-m-d') . "&idSubtable=1&format=JSON&token_auth=" . $token;
        $json_raw;
        $json_done;
        $json_raw = file_get_contents($url);
        $json_done = json_decode($json_raw, true);
        $subscribers = $this->subscriberRepository->findAll();
        foreach ($subscribers as $subscriber) {
            $collections = $subscriber->getCollections();
            $websites = $subscriber->getWebsites();
            $newJsonCollections = array();
            $newJsonWebsites = array();
            //adding the things that arent in there
            //maybe an own action or integration with the other part of the action
            foreach ($collections as $collection) {
                $isNew = true;
                foreach ($json_done as $item) {
                    if ($item["label"] == $collection->getName()) {
                        $isNotNew = false;
                    }
                }
                if($isNew){
                array_push($newJsonCollections, $collection);
                }
            }

            foreach ($websites as $website) {
                $isNew = true;
                foreach ($json_done as $item) {
                    if ($item["label"] == $website->getName()) {
                        $isNew = false;
                    }
                }
            }
            if($isNew){
                array_push($newJsonCollections, $collection);
            }
            

            //var_dump($newJsonCollections);
            var_dump($newJsonWebsites);

            //Collections
            foreach ($collections as $collection) {

                //how do i get an comparison between the collections I have and the new ones, to find witch new are new and witch are already there
                foreach ($json_done as $item) {
                    if ($collection->getName() == $item["label"]) {
                        if (is_null($collection->getVisits()) == false) {
                            $visits = $collection->getVisits();
                            $visits->setUniqueVisitors($item["sum_daily_nb_uniq_visitors"]);
                            $visits->setPageViews($item["nb_visits"]);

                            //$visits->setMonth($date);
                            $collection->setVisits($visits);
                        } else {

                            //maybe an flash message warning
                        }
                    } else {
                    }
                }
            }

            //Websites
            foreach ($websites as $website) {
                foreach ($json_done as $item) {

                    //repeat, for the most part, of what is written above
                    if ($website->getName() == $item["label"]) {
                        if (is_null($website->getVisits()) == false) {
                            $visits = $website->getVisits();
                            $visits->setUniqueVisitors($item["sum_daily_nb_uniq_visitors"]);

                            //$visits->setUniqueVisitors(100);
                            $visits->setPageViews($item["nb_visits"]);

                            //$visits->setMonth($date);
                            $website->setVisits($visits);
                        } else {

                            //maybe an flash message warning
                        }
                    } else {
                    }
                }
            }
            $subscriber->setCollections($collections);
            $subscriber->setWebsites($websites);
            $this->subscriberRepository->update($subscriber);
        }

        //$this->redirect('list');
        //return $successfullyExecuted;
    }

        
}