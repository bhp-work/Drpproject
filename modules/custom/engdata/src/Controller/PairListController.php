<?php

namespace Drupal\engdata\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class PairListController.
 *
 * @package Drupal\engdata\Controller
 */
class PairListController extends ControllerBase {


  public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'engdata_description',
        '#description' => 'Manage exchange pair',
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
     'connection_id'=>    t('Id'),
      'User_ID' => t('User'),
        'exchange' => t('Exchange'),
        'pair'=>t('Pair'),       
        'enabled' => t('Enabled'),
        'last_updated' => t('Last Update'),
        //'Delete' => t('Delete'),
        'Edit' => t('Edit'),
    );

    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
$uid= $user->get('uid')->value;
//select records from table
    $query = \Drupal::database()->select('user_pair_connection', 'e');
    $query->condition('user_id',-1);
       $query->fields('e');
      $results = $query->execute()->fetchAll();
        $rows=array();
    foreach($results as $data){
       // $delete = Url::fromUserInput('/engdata/delete/'.$data->Eng_Config_ID);
        $edit   = Url::fromUserInput('/engdata/pairconn/form?num='.$data->connection_id);

      //print the data from table
             $rows[] = array(
            'Id' =>$data->connection_id,
            'User ID' => ($data->user_id==-1?'Admin':$data->user_id),
                'Exchange' => $data->exchange,
                'Pair' => $data->pair,
                'Enabled' => ($data->enabled=='1'?'Yes':'No'),
                 'Last Update' => $data->last_updated,
              //   \Drupal::l('Delete', $delete),
                 \Drupal::l('Edit', $edit),
            );

    }
    //display data in site
    $form['table'] = [
            '#type' => 'table',
            '#prefix' => '<h4>Manage exchange pairs</h4> </hr> <a href="../engdata/pairconn/form" class="btn btn-primary">Add new pair</a></hr>',
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
