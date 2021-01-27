<?php

namespace Drupal\engdata\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Class UserConnectionController.
 *
 * @package Drupal\engdata\Controller
 */
class UserConnectionController extends ControllerBase
{

    public function getContent()
    {
        // First we'll tell the user what's going on. This content can be found
        // in the twig template file: templates/description.html.twig.
        // @todo: Set up links to create nodes and point to devel module.
        $build = [
            'description' => [
              //  '#theme' => 'engdata_description',
                '#description' => 'Manage user exchange connections',
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
        //ConnID, MasterExchID, UserConnName, Exch_API_Public, Exch_API_PublicVersion, Exch_API_Private, Exch_API_PrivateVersion, UserID, ExchActive, Timestamp
        //create table header
        $header_table = array(
            'ID' => t('Id'),
            'UserConnName' => t('Connection'),
            'Exchange' => t('Exchange'),
            'ExchActive' => t('Active'),
            'Delete' => t('Delete'),
            'Edit' => t('Edit'),
        );

        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $uid = $user->get('uid')->value;
        //select records from table
        // $query = \Drupal::database()->select('xb_exchcurrencies', 'e');
        // $query->condition('UserID', 0);
        // $query->fields('e');
        // $results = $query->execute()->fetchAll();

        $query = db_select('xb_exchconnection', 'p');
        $query->join('xb_masterexch', 'm', 'm.MasterExchID = p.MasterExchID');
        $result = $query
            ->fields('p', array('ConnID', 'MasterExchID', 'UserConnName', 'ExchActive'))
            ->fields('m', array('MasterExchName'))
            ->condition('p.UserID', $uid)
            ->orderBy("m.MasterExchName")
            ->execute();
        $results = $query->execute()->fetchAll();

        $rows = array();
        $i=1;
        foreach ($results as $data) {
             $delete = Url::fromUserInput('/conn/userexconn/delete/'.$data->ConnID);
            $edit = Url::fromUserInput('/conn/userexconn?num=' . $data->ConnID);

            //print the data from table
            $rows[] = array(
               
                'ID' => $i,
                'Exchange' => $data->MasterExchName,
                'UserConnName' => $data->UserConnName,
                'Active' => $data->ExchActive,
                \Drupal::l('Delete', $delete),
                \Drupal::l('Edit', $edit),
            );
            $i++;

        }
        global $base_url;
        //$response = new RedirectResponse($base_url . '/list/masterexchange');
        //display data in site
        $form['table'] = [
            '#type' => 'table',
            '#prefix' => '<h4>Manage connections</h4> </hr> <a href="'.$base_url .'/conn/userexconn" class="btn btn-primary">Add new connection</a></hr><br><br>',
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
