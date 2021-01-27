<?php

namespace Drupal\engdata\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Class PairListController.
 *
 * @package Drupal\engdata\Controller
 */
class PairListController extends ControllerBase
{

    public function getContent()
    {
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
    public function display()
    {
        /*return [
        '#type' => 'markup',
        '#markup' => $this->t('Implement method: display with parameter(s): $name'),
        ];*/
        //ID, ExchID, Pair, Currency, Category, UserID, Active
        //create table header
        $header_table = array(
            'ID' => t('Id'),
            'ExchID' => t('ExchID'),
            'Exchange' => t('Exchange'),
            'Pair' => t('Pair'),
            'xb_pair' => t('xb_pair'),
            'ex_pair' => t('ex_pair'),
            'Currency' => t('Currency'),
            'Category' => t('Category'),
            'UserID' => t('UserID'),
            'Active' => t('Active'),
            'Delete' => t('Delete'),
            'Edit' => t('Edit'),
        );

        // $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        // $uid = $user->get('uid')->value;
        //select records from table
        // $query = \Drupal::database()->select('xb_exchcurrencies', 'e');
        // $query->condition('UserID', 0);
        // $query->fields('e');
        // $results = $query->execute()->fetchAll();

        $query = db_select('xb_exchcurrencies', 'p');
        $query->join('xb_masterexch', 'm', 'm.MasterExchID = p.ExchID');
        $result = $query
            ->fields('p', array('ID', 'ExchID', 'Pair', 'Currency', 'Category', 'UserID', 'Active'))
            ->fields('m', array('MasterExchName','SplitBy'))
            ->orderBy("m.MasterExchName")
            ->execute();
        $results = $query->execute()->fetchAll();

        $rows = array();
        foreach ($results as $data) {
             $delete = Url::fromUserInput('/exchangepaircon/delete/'.$data->ID);
            $edit = Url::fromUserInput('/exchangepaircon?num=' . $data->ID);

            //print the data from table
            $rows[] = array(
                // 'ID' => $data->ID,
                // 'User ID' => ($data->user_id == 0 ? 'Admin' : $data->UserID),
                // 'Exchange' => $data->MasterExchName,
                // 'Pair' => $data->pair,
                // 'Enabled' => ($data->enabled == '1' ? 'Yes' : 'No'),
                // 'Last Update' => $data->last_updated,
                //$string =; 

                'ID' => $data->ID,
                'ExchID' => $data->ExchID,
                'Exchange' => $data->MasterExchName,
                'Pair' => $data->Pair,
                'xb_pair' => preg_replace('/[^A-Za-z0-9\-]/', '_', $data->Pair),
                'ex_pair' => preg_replace('/[^A-Za-z0-9\-]/', $data->SplitBy, $data->Pair),
                'Currency' => $data->Currency,
                'Category' => $data->Category,
                'UserID' =>  ($data->UserID == 0 ? 'Admin' : $data->UserID),
                'Active' => $data->Active,
                \Drupal::l('Delete', $delete),
                \Drupal::l('Edit', $edit),
            );

        }
        global $base_url;
        //$response = new RedirectResponse($base_url . '/list/masterexchange');
        //display data in site
        $form['table'] = [
            '#type' => 'table',
            '#prefix' => '<h4>Manage exchange pairs</h4> </hr>', 
            //<a href="'.$base_url .'/exchangepaircon" class="btn btn-primary">Add new pair</a></hr><br><br>',
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
