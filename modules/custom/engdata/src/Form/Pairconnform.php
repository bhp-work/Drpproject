<?php

namespace Drupal\engdata\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class pairconnform.
 *
 * @package Drupal\engdata\Form
 */
class pairconnform extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'pairconnform_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $currtime = time(); // get the Unix timestamp of now
        // $fortime = format_date( time(), 'Y-m-d H:i:s'); // using the user's timezone preferences
        $fortime = date('Y-m-d H:i:s', $currtime);

// Load the current user.
        // $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        // $uid = $user->get('uid')->value;
//  dump($user);
        //  exit;

        $conn = Database::getConnection();
        $record = array();
        if (isset($_GET['num'])) {
            // $query = $conn->select('xb_exchcurrencies', 'e')
            //     ->condition('ID', $_GET['num'])
            //     ->fields('e');
            // $record = $query->execute()->fetchAssoc();
            $query = db_select('xb_exchcurrencies', 'p');
            $query->join('xb_masterexch', 'm', 'm.MasterExchID = p.ExchID');
            $query->condition('ID', $_GET['num']);
            $result = $query
                ->fields('p', array('ID', 'ExchID', 'Pair', 'Currency', 'Category', 'UserID', 'Active'))
                ->fields('m', array('MasterExchName', 'SplitBy'))
                ->orderBy("m.MasterExchName")
                ->execute();
            $record = $query->execute()->fetchAssoc();
        }
//  dump($record);
//  dump($result);
//  exit;
        // exit;
        $form['ID'] = array (
          '#type' => 'hidden',
          '#title' => t('ID:'),
          '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
          '#default_value' => (isset($record['ID']) && $_GET['num']) ? $record['ID']:'',
          '#attributes' => array('readonly' => 'readonly'),
           );
        //    $form['user_id'] = array (
        //     '#type' => 'textfield',
        //     '#title' => t('User ID:'),
        //     '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
        //    // '#default_value' => (isset($record['user_id']) && $_GET['num']) ? $record['user_id']:'Admin',
        //    '#default_value' =>'Admin',
        //      );

        $form['exchange'] = array(
            '#type' => 'textfield',
            '#title' => t('Exchange:'),
            '#required' => true,
            //'#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
            '#default_value' => (isset($record['MasterExchName']) && $_GET['num']) ? $record['MasterExchName'] : '',
        );
        // $default = 'Yes';
        $form['Pair'] = array(
            '#type' => 'textfield',
            '#title' => t('Pair:'),
            '#required' => true,
            // '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
            '#default_value' => (isset($record['Pair']) && $_GET['num']) ? $record['Pair'] : '',
        );
        $form['Active'] = array(
            '#type' => 'select',
            '#title' => ('Enabled:'),
            '#required' => true,
            '#options' => array(
                '1' => t('Yes'),
                '0' => t('No'),

                //   '#default_value' => (isset($record['Active']) && $_GET['num']) ? $record['Active']:'',
                //  '#default_value' =>$default,
            ),
        );
        $form['Currency'] = array(
            '#type' => 'select',
            '#title' => ('Currency:'),
            '#required' => true,
            '#options' => array(
                '1' => t('1'),
                '2' => t('2'),

                //   '#default_value' => (isset($record['Currency']) && $_GET['num']) ? $record['Currency']:'',
                //  '#default_value' =>$default,
            ),
        );

        $form['Category'] = array(
            '#type' => 'select',
            '#title' => ('Category:'),
            '#required' => true,
            '#options' => array(
                '1' => t('1'),
                '2' => t('2'),

                //   '#default_value' => (isset($record['Category']) && $_GET['num']) ? $record['Category']:'',
                //  '#default_value' =>$default,
            ),
        );

        // $form['last_updated'] = array (
        //   '#type' => 'textfield',
        //   '#title' => t('Last Update'),
        //   '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
        //   '#default_value' => (isset($record['last_updated']) && $_GET['num']) ? $record['last_updated']:'',
        //  // '#default_value' => $fortime,
        //    );

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'save',
            //'#value' => t('Submit'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {

        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        //ID, user_id, exchange, pair, enabled, last_updated
        $field = $form_state->getValues();
        $ID = $field['ID'];
        //echo "$name";
        //$user_id=($field['user_id']=='Admin'?-1:$field['user_id']);
        //$user_id=-1;
      //  $MasterExchName = $field['MasterExchName'];
        $Pair = $field['Pair'];
        $Active = $field['Active'];
        $Currency = $field['Currency'];
        $Category = $field['Category'];
        $currtime = time(); // get the Unix timestamp of now
        $fortime = date('Y-m-d H:i:s', $currtime);

// // strip out all whitespace
//         $exchange = preg_replace('/\s*/', '', $exchange);
// // convert the string to all lowercase
//         $exchange = strtolower($exchange);
// strip out all whitespace
        $Pair = preg_replace('/\s*/', '', $Pair);
// convert the string to all uppercase
        $Pair = strtoupper($Pair);

        // ID, user_id, exchange, pair, enabled, last_updated
       // ID', 'ExchID', 'Pair', 'Currency', 'Category', 'UserID', 'Active
        if (isset($_GET['num'])) {
            $field = array(
              //  'exchange' => $exchange,
                'Pair' => $Pair,
                'Active' => $Active,
                'Currency' => $Currency,
                'Category' => $Category,
                //'last_updated' => $fortime,
            );
            $query = \Drupal::database();
            $query->update('xb_exchcurrencies')
                ->fields($field)
                ->condition('ID', $_GET['num'])
                ->execute();
            drupal_set_message("succesfully updated");
            $form_state->setRedirect('engdata.display_Pair_Connection_controller');

        } else {
            $field = array(
                'UserID' => 0,
                'Pair' => $Pair,
                'Active' => $Active,
                'Currency' => $Currency,
                'Category' => $Category,
               // 'last_updated' => $fortime,
            );
            $query = \Drupal::database();
            $query->insert('xb_exchcurrencies')
                ->fields($field)
                ->execute();
            drupal_set_message("succesfully saved");

            $form_state->setRedirect('engdata.display_Pair_Connection_controller');

        }
    }

}
