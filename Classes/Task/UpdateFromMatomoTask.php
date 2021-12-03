<?php
namespace Slub\MatomoReporter\Task;
//do with cron job if possible with ddev 
use Slub\MatomoReporter\Controller\SubscriberController;
class UpdateFromMatomoTask extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
        public function execute() {
            \Slub\MatomoReporter\Controller\SubscriberController::updateMatomoDataAction();
        }
}