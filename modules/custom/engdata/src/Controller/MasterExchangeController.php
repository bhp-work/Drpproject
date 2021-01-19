<?php

namespace Drupal\engdata\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class MasterExchangeController.
 *
 * @package Drupal\engdata\Controller
 */
class MasterExchangeController extends ControllerBase
{

    public function getContent()
    {
        // First we'll tell the user what's going on. This content can be found
        // in the twig template file: templates/description.html.twig.
        // @todo: Set up links to create nodes and point to devel module.
        $build = [
            'description' => [
                '#theme' => 'Master Exchange View',
                '#description' => 'Manage exchanges',
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
    public function display()
    {
        //create table header
        $header_table = array(
            'MasterExchID' => t('ID'),
            'MasterExchName' => t('Exchange'),
            'MasterExchActive' => t('Enabled'),
            'ApiEndpoint' => t('API endpoint'),
            'PairFormat' => t('Pair format'),
            'SplitBy' => t('Split by'),            
            'Edit' => t('Edit'),
            'Delete' => t('Delete'),
        );

     //   $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
       // $uid = $user->get('uid')->value;
//select records from table
        $query = \Drupal::database()->select('xb_masterexch', 'e');
       // $query->condition('user_id', -1);
        $query->fields('e');
        $query->orderBy("e.MasterExchName");
        $results = $query->execute()->fetchAll();
        $rows = array();
        foreach ($results as $data) {
            $delete = Url::fromUserInput('/masterexchange/delete/'.$data->MasterExchID);
            $edit = Url::fromUserInput('/masterexchange?num=' . $data->MasterExchID);

    //  MasterExchID, MasterExchName, MasterExchActive, Timestamp, ApiEndpoint, PairFormat, SplitBy
            //print the data from table
            $rows[] = array(
                'Id' => $data->MasterExchID,
              //  'User ID' => ($data->user_id == -1 ? 'Admin' : $data->user_id),
                'Exchange' => $data->MasterExchName,               
                'Enabled' => ($data->ApiEndpoint == '1' ? 'Yes' : 'No'),
                'API endpoint' => $data->ApiEndpoint,
                'Pair format' => $data->PairFormat,
                'Split by' => $data->SplitBy,
                \Drupal::l('Delete', $delete),
                \Drupal::l('Edit', $edit),
            );

        }
        //display data in site
        $form['table'] = [
            '#type' => 'table',
            '#prefix' => '<h4>Manage exchanges</h4> </hr> <a href="../MasterExchange" class="btn btn-primary">Add new exchange</a></hr></br></br>',
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
