<?php

namespace Drupal\engine_settings\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'CustomBlock' Block.
 *
 * @Block(
 *   id = "Custom_block",
 *   admin_label = @Translation("Custom block"),
 *   category = @Translation("Custom World"),
 * )
 */
class CustomBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

// Create an object of type Select.
$db = \Drupal::database();
$query = $db->select('eng_config', 'e');
 
// Add extra detail to this query object: a condition, fields and a range.
$query->condition('e.User_ID', 0, '<>');

// $query->fields('e', ['Eng_Config_ID','User_ID', 'Eng_Config_Parent_ID', 'Eng_Config_Process_Name', 'Eng_Config_Enabled', 'Eng_Config_Run_State','Eng_Config_Options', 'Eng_Config_Last_Status', 'Eng_Config_Last_Update']);
$query->fields('e');
// $query->range(0, 50);
$result = $query->execute()->fetchAll();
 $Last_Status=[];

 foreach ($result as $record) {
//   // Do something with each $record.
// //   dump($record);
// //   exit;
$Last_Status[]=$record->Eng_Config_Process_Name .' | '. $record->Eng_Config_Run_State;
//->getValue()[0]['value'];
//$Last_Status[]=$result->fetchField('Eng_Config_Process_Name');
// $record = $result->fetchField('');
// dump($record);Eng_Config_Process_Name
// exit;
}

//   dump($Last_Status);
//    exit;
 $output=$Last_Status;
 

    //  $output='Hello World testing';
    return [
     '#theme' => 'custom-block',
      '#data' => $output,
        '#cache' => ['max-age'=>0],
    ];
  }

}