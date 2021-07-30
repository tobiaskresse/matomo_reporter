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
/**
 * SubscriberController
 */
class SubscriberController extends AbstractController
{

  //  /**
  //   * subscriberRepository
  //   * 
  //   * @var \Slub\MatomoReporter\Domain\Repository\SubscriberRepository
  //   */
  //  protected $subscriberRepository = null;
//
  //  /**
  //   * @param \Slub\MatomoReporter\Domain\Repository\SubscriberRepository $subscriberRepository
  //   */
  //  public function injectSubscriberRepository(\Slub\MatomoReporter\Domain\Repository\SubscriberRepository $subscriberRepository)
  //  {
  //      $this->subscriberRepository = $subscriberRepository;
  //  }
    /**
     * action list
     * 
     * @return void
     */
    public function listAction()
    {
        $subscribers = $this->subscriberRepository->findAll();
        $this->view->assign('subscribers', $subscribers);
    }
    
    /**
     * action show
     * 
     * @param \Slub\MatomoReporter\Domain\Model\Subscriber $subscriber
     * @return void
     */
    public function showAction(\Slub\MatomoReporter\Domain\Model\Subscriber $subscriber)
    {
        $this->view->assign('subscriber', $subscriber);
    }
    
    /**
     * action new
     * 
     * @return void
     */
    public function newAction()
    {            
        //$this->updateMatomoDataAction();
        
    }
    
    /**
     * action create
     * 
     * @param \Slub\MatomoReporter\Domain\Model\Subscriber $newSubscriber
     * @return void
     */
    public function createAction(\Slub\MatomoReporter\Domain\Model\Subscriber $newSubscriber)
    {
        
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->subscriberRepository->add($newSubscriber);
        $this->redirect('list');
    }
    
    /**
     * action edit
     * 
     * @param \Slub\MatomoReporter\Domain\Model\Subscriber $subscriber
     * @ignorevalidation $subscriber
     * @return void
     */
    public function editAction(\Slub\MatomoReporter\Domain\Model\Subscriber $subscriber)
    {
        $this->view->assign('subscriber', $subscriber);
    }
    
    /**
     * action update
     * 
     * @param \Slub\MatomoReporter\Domain\Model\Subscriber $subscriber
     * @return void
     */
    public function updateAction(\Slub\MatomoReporter\Domain\Model\Subscriber $subscriber)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->subscriberRepository->update($subscriber);
        $this->redirect('list');
    }
    
    /**
     * action delete
     * 
     * @param \Slub\MatomoReporter\Domain\Model\Subscriber $subscriber
     * @return void
     */
    public function deleteAction(\Slub\MatomoReporter\Domain\Model\Subscriber $subscriber)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->subscriberRepository->remove($subscriber);
        $this->redirect('list');
    }
    
    public function createMatomoDataAction()
    {
        //$date = new \DateTime;
        $token = "";
        $url = "https://matomo.slub-dresden.de/index.php?module=API&method=CustomVariables.getCustomVariablesValuesFromNameId&idSite=412&period=month&date=2021-05-05&idSubtable=1&format=JSON&token_auth=" . $token;
        // variable DateTime
        //$url = "https://matomo.slub-dresden.de/index.php?module=API&method=CustomVariables.getCustomVariablesValuesFromNameId&idSite=412&period=month&date=" . $date->format('y-m-d') . "&idSubtable=1&format=JSON&token_auth=" . $token;
        $json_raw;
        $json_done;
        $json_raw = file_get_contents($url);
        $json_done = json_decode($json_raw, true);
        
        
    }
    
    //GerneralUtility Make Instance (objct)
    /**
     * action updateMatomoData
     * 
     * Updates the information that is attached to the Subscribers
     * 
     * @return void
     */
    public function updateMatomoDataAction()
    {
        $date = new \DateTime;
        //var_dump($date);
        $token = "";
        $url = "https://matomo.slub-dresden.de/index.php?module=API&method=CustomVariables.getCustomVariablesValuesFromNameId&idSite=412&period=month&date=2021-05-05&idSubtable=1&format=JSON&token_auth=" . $token;
        // variable DateTime
        //$url = "https://matomo.slub-dresden.de/index.php?module=API&method=CustomVariables.getCustomVariablesValuesFromNameId&idSite=412&period=month&date=" . $date->format('y-m-d') . "&idSubtable=1&format=JSON&token_auth=" . $token;
        $json_raw;
        $json_done;
        $json_raw = file_get_contents($url);
        $json_done = json_decode($json_raw, true);
        
        $subscribers = $this->subscriberRepository->findAll();
        
        foreach($subscribers as $subscriber)
        {
            $collections = $subscriber->getCollections();
            $websites = $subscriber->getWebsites();
            
            $newJsonCollections = array();
            $newJsonWebsites = array();
            
            //adding the things that arent in there
            foreach($json_done as $item)
            {
                foreach($collections as $collection)
                {
                    if($item["label"] == $collection->getName())
                    {
                        break;
                    }
                    else{
                        array_push($newJsonCollections, $collection);
                    }
                }


                foreach($websites as $website)
                {
                    if($item["label"] == $website->getName())
                    {
                        break;
                    }else{
                        array_push($newJsonWebsites, $website);
                    }
                }
            }
            //var_dump($newJsonCollections);
            var_dump($newJsonWebsites);


            //Collections
            foreach($collections as $collection)
            { //how do i get an comparison between the collections I have and the new ones, to find witch new are new and witch are already there
                foreach($json_done as $item)
                {
                    if($collection->getName() == $item["label"])
                    {
                        if(is_null($collection->getVisits()) == false)
                        {
                            $visits = $collection->getVisits();
                            $visits->setUniqueVisitors($item["sum_daily_nb_uniq_visitors"]);
                            $visits->setPageViews($item["nb_visits"]);
                            //$visits->setMonth($date);
                            $collection->setVisits($visits);
                        }else 
                        {
                            //maybe an flash message warning 
                        }
                    }else{}
                }
            }
            
            //Websites
            foreach($websites as $website)
            {
                foreach($json_done as $item)
                {
                    //repeat, for the most part, of what is written above

                    if($website->getName() == $item["label"])
                    {
                        if(is_null($website->getVisits()) == false)
                        {
                            $visits = $website->getVisits();
                            $visits->setUniqueVisitors($item["sum_daily_nb_uniq_visitors"]);
                            //$visits->setUniqueVisitors(100);
                            $visits->setPageViews($item["nb_visits"]);
                            //$visits->setMonth($date);
                            $website->setVisits($visits);
                        }else 
                        {
                            //maybe an flash message warning
                            
                        }
                    }else{}
                }
            }
            $subscriber->setCollections($collections);
            $subscriber->setWebsites($websites);
            $this->subscriberRepository->update($subscriber);
        }
        //$this->redirect('list');
    }


    /**
     * action sendMails
     * 
     * sends an Email to all subscribers
     * 
     * @return void
     */
    public function sendMailsAction()
    {
        $subscribers = $this->subscriberRepository->findAll();
        
        foreach($subscribers as $subscriber)  
        {  
            $collectionNames = "";
            $collectionNamesHtml = "";
            $websiteNames = "";
            $websiteNamesHtml = "";
            foreach ($subscriber->getCollections() as $item)
            {
                if($collectionNames == "")
                {
                    $collectionNames = $collectionNames . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    $collectionNamesHtml = $collectionNamesHtml . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                } else{
                    $collectionNames = $collectionNames . ",\n" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    $collectionNamesHtml = $collectionNamesHtml . ",</br>" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                }
            }

            foreach ($subscriber->getWebsites() as $item) 
            {
                if($websiteNames == "")
                {
                    $websiteNames = $websiteNames . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    $websiteNamesHtml = $websiteNamesHtml . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                } else{
                    $websiteNames = $websiteNames . ",\n" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                    $websiteNamesHtml = $websiteNamesHtml . ",</br>" . $item->getName() . " with " . $item->getVisits()->getPageViews() . " Page Visits and " . $item->getVisits()->getUniqueVisitors() . " unique Visitors";
                }
            }

            // Create the message
            $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);

            // Prepare and send the message
            $mail

               // Give the message a subject
               ->setSubject('Monthly Report')

               // Set the From address with an associative array
               ->setFrom(array('Tobias.kresse@slub-dresden.de' => 'John Doe'))

               // Set the To addresses with an associative array
               ->setTo(array($subscriber->getEmail() => $subscriber->getName()))

               // Give it a body
               ->setBody("Collections: \n". $collectionsNames ."\n\nWebsites:\n". $websiteNames)

               // And optionally an alternative body
               ->addPart("<p>Collections: </br> $collectionNamesHtml </p><p>Websites:</br> $websiteNamesHtml </p>" ,'text/html')

               // Optionally add any attachments
               //->attach(\Swift_Attachment::fromPath('my-document.pdf'))

               // And finally do send it
               ->send()
            ;
        }
        $this->redirect('list');
    }

    
    
}
