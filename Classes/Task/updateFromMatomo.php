<?php
namespace Slub\MatomoReporter\Task;
//do with cron job if possible with ddev 
use Slub\MatomoReporter\Controller\AbstractController;
class updateFromMatomo extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
        public function execute() {
            return true;    
        }
}