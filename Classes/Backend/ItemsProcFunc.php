<?php
namespace Slub\matomo_reporter\Backend;

/***
 * This file is part of the "matomo_reporter" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * 
 *  (c) 2021 Tobias Kreße <Tobias.Kresse@slub-dresden.de>, SLUB Dresden
 * 
 ***/

class  ItemsProcFunc
{
/**
  * populating the option for websites
  *
  * @param array &$config configuration array
  */
  public function user_websitesSideBySide(array &$config)
  {
    //api commmunication and decoding
    $apiAnswer = file_get_contents('https://matomo.slub-dresden.de/index.php?module=API&method=CustomVariables.getCustomVariablesValuesFromNameId&idSite=412&period=month&date=2021-05-05&idSubtable=1&format=JSON&token_auth=[replace with token]'
    );
    $apiAnswerDecode = json_decode ($apiAnswer, true);

    
    //constructing the wanted array
    foreach ($apiAnswerDecode as $item) 
    {
      $newItems[] = [$item['label']];
    }
    $config['items'] = $newItems;
  }


  /**
  * populating the option for collections
  *
  * @param array &$config configuration array
  */
  public function user_collectionsSideBySide(array &$config)
  {
    //api commmunication and decoding
    $apiAnswer = file_get_contents(''
    );
    $apiAnswerDecode = json_decode ($apiAnswer, true);

    
    //constructing the wanted array
    foreach ($apiAnswerDecode as $item) 
    {
      $newItems[] = [$item['label']];
    }
    $config['items'] = $newItems;
  }
  
}
