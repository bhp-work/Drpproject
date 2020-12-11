<?php

namespace Drupal\engdata\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\engdata\Controller
 */
class DisplayTableController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'engdata_description',
        '#description' => 'Manage eng_config db table',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    /*return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: display with parameter(s): $name'),
    ];*/

    //create table header
    $header_table = array(
     'Eng_Config_ID'=>    t('SrNo'),
      'User_ID' => t('User ID'),
        'Eng_Config_Parent_ID' => t('Parent ID'),
        'Eng_Config_Process_Name'=>t('Process Name'),
        'Eng_Config_Enabled' => t('Enabled'),
        'Eng_Config_Run_State' => t('Run State'),
        'Eng_Config_Options' => t('Options'),
        'Eng_Config_Last_Status' => t('Last Status'),
        'Eng_Config_Last_Update' => t('Last Update'),
        'Delete' => t('Delete'),
        'Edit' => t('Edit'),
    );

    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
$uid= $user->get('uid')->value;
//select records from table
    $query = \Drupal::database()->select('eng_config', 'e');
    $query->condition('User_ID',$uid);
       $query->fields('e', ['Eng_Config_ID', 'User_ID', 'Eng_Config_Parent_ID', 'Eng_Config_Process_Name', 'Eng_Config_Enabled', 'Eng_Config_Run_State', 'Eng_Config_Options', 'Eng_Config_Last_Status', 'Eng_Config_Last_Update']);
      $results = $query->execute()->fetchAll();
        $rows=array();
    foreach($results as $data){
        $delete = Url::fromUserInput('/engdata/delete/'.$data->Eng_Config_ID);
        $edit   = Url::fromUserInput('/engdata/form?num='.$data->Eng_Config_ID);

      //print the data from table
             $rows[] = array(
            'SrNo' =>$data->Eng_Config_ID,
            'User ID' => $data->User_ID,
                'Parent ID' => $data->Eng_Config_Parent_ID,
                'Process Name' => $data->Eng_Config_Process_Name,
                'Enabled' => ($data->Eng_Config_Enabled=='1'?'Yes':'No'),
                'Run State' => $data->Eng_Config_Run_State,
                'Options' => $data->Eng_Config_Options,
                'Last Status' => $data->Eng_Config_Last_Status,
                'Last Update' => $data->Eng_Config_Last_Update,
                 \Drupal::l('Delete', $delete),
                 \Drupal::l('Edit', $edit),
            );

    }
    //display data in site
    $form['table'] = [
            '#type' => 'table',
            '#prefix' => '<h4>Engine Configuration settings</h4> </hr> <a href="../engdata/form" class="btn btn-primary">Add new setting</a></hr>',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No data found'),
        ];

        $form['pager'] = [
          '#type' => 'pager',
        ];
//        echo '<pre>';print_r($form['table']);exit;
        return $form;

  }

}
