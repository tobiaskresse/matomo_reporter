<?php
defined('TYPO3_MODE') || die('Access denied.');

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][Slub\MatomoReporter\Task\UpdateFromMatomoTask::class] = array(
            'extension' => 'matomo_reporter',
            'title' => 'LLL:EXT:matomo_reporter/locallang.xlf:UpdateFromMatomoTask.name',
            'description' => 'LLL:EXT:matomo_reporter/locallang.xlf:UpdateFromMatomoTask.description',
            'additionalFields' => Slub\MatomoReporter\Task\UpdateFromMatomoTask::class
    );
}
if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][Slub\MatomoReporter\Task\SendMailsTask::class] = array(
            'extension' => 'matomo_reporter',
            'title' => 'LLL:EXT:matomo_reporter/locallang.xlf:SendMailsTask.name',
            'description' => 'LLL:EXT:matomo_reporter/locallang.xlf:SendMailsTask.description',
            'additionalFields' => Slub\MatomoReporter\Task\SendMailsTask::class
    );
}