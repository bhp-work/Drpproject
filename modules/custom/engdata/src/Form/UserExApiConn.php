<?php

namespace Drupal\engdata\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class UserExApiConn.
 *
 * @package Drupal\engdata\Form
 */
class userexapiconn extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'userexapiconn_form';
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
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $uid = $user->get('uid')->value;
//  dump($user);
        //  exit;

        $conn = Database::getConnection();
        $record = array();
        if (isset($_GET['num'])) {
            // $query = $conn->select('xb_exchconnection', 'c')
            // $query->join('xb_masterexch', 'm', 'm.MasterExchID = c.MasterExchID');
            // $query->condition('c.ConnID', $_GET['num'])
            // $query->fields('e', 'm.MasterExchName');
            // $record = $query->execute()->fetchAssoc();
            $query = db_select('xb_exchconnection', 'p');
            $query->join('xb_masterexch', 'm', 'm.MasterExchID = p.MasterExchID');
            $query->condition('ConnID', $_GET['num']);
            $result = $query
                ->fields('p')
                ->fields('m', array('MasterExchName'))
                // ->orderBy("m.MasterExchName")
                ->execute();
            $record = $query->execute()->fetchAssoc();

        }

// dump($record);
        // exit;
        $form['UserConnName'] = array(
            '#type' => 'textfield',
            '#title' => t('Connection Name:'),
            '#required' => true,
            '#default_value' => (isset($record['UserConnName']) && $_GET['num']) ? $record['UserConnName'] : '',
            // '#attributes' => array('class' => array('col-xs-5')),
            // '#prefix' =>  '<div class="col-xs-5">',
            // '#suffix' => '</div>',
            // '#label_classes' => [
            //     'col-lg-2'
            // ]
        );
        $form['exchange'] = [
            '#title' => t('Exchange:'),
           // '#attributes' => (isset($_GET['num']))? array('readonly' => 'readonly','disabled'=>'TRUE'):array('disabled'=>'FALSE'),
            '#type' => 'select',
            '#empty_value' => '',
            '#required' => true,
            '#empty_option' => '- Select -',
            '#default_value' => (isset($values['MasterExchID']) ? $values['MasterExchID'] : ''),
            '#options' => _get_exchanges(),
        ];

        $form['ExchActive'] = array(
            '#type' => 'select',
            '#title' => ('Enabled:'),
            '#required' => true,
            '#options' => array(
                '1' => t('Yes'),
                '0' => t('No'),
                //   '#default_value' => (isset($record['ExchActive']) && $_GET['num']) ? $record['ExchActive']:'',
            ),
            // '#prefix' =>  '<div class="col-xs-5">',
            // '#suffix' => '</div>',
        );

        $form['api_key'] = array(
            '#type' => 'textfield',
            '#title' => t('Api Key'),
            '#size' => '64',
            '#required' => true,
          //  '#attributes' => (isset($_GET['num']))? array('readonly' => 'readonly','disabled'=>'TRUE'):array('disabled'=>'FALSE'),
            '#default_value' => (isset($record['Exch_API_PublicVersion']) && $_GET['num']) ? $record['Exch_API_PublicVersion'] : '',

        );
        $form['api_s_key'] = array(
            '#type' => 'textfield',
            '#title' => t('Api Secret Key'),
            '#size' => '64',
            '#required' => true,
           // '#attributes' => (isset($_GET['num']))? array('readonly' => 'readonly','disabled'=>'TRUE'):array('disabled'=>'FALSE'),
            '#default_value' => (isset($record['Exch_API_PrivateVersion']) && $_GET['num']) ? $record['Exch_API_PrivateVersion'] : '',
        );

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'save',

        ];
        if (isset($_GET['num'])) {
            $form['exchange']['#disabled'] = true; 
            $form['api_key']['#disabled'] = true; 
            $form['api_s_key']['#disabled'] = true; 
        }

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $field = $form_state->getValues();
        $api_key = "";
        $api_s_key = "";
        if (!empty($field)) {
            $api_key = $field['api_key'];
            $api_s_key = $field['api_s_key'];
        }
        parent::validateForm($form, $form_state);
        //    $name = $form_state->getValue('candidate_name');
        if (!isset($_GET['num'])) {

            if (strlen($api_key) != 64) {
                $form_state->setErrorByName('api_key', $this->t('Please enter valid API key '));
            }
            if (!ctype_alnum($api_key)) {
                $form_state->setErrorByName('api_key', $this->t('API key must not have space or alphanumeric characters'));
            }

            if (strlen($api_s_key) != 64) {
                $form_state->setErrorByName('api_s_key', $this->t('Please enter valid API secret key'));
            }
            if (!ctype_alnum($api_s_key)) {
                $form_state->setErrorByName('api_s_key', $this->t('API secret key must not have space or alphanumeric characters'));
            }
        }
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    { // Load the current user.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $uid = $user->get('uid')->value;
        //  ConnID, MasterExchID, UserConnName, Exch_API_Public, Exch_API_PublicVersion, Exch_API_Private, Exch_API_PrivateVersion, UserID, ExchActive, Timestamp
        $field = $form_state->getValues();
        $MasterExchID = $field['exchange'];
        $UserConnName = $field['UserConnName'];
        $Exch_API_Public = $field['api_key'];
        $Exch_API_PublicVersion = '****' . substr($Exch_API_Public, -4);
        $Exch_API_Private = $field['api_s_key'];
        $Exch_API_PrivateVersion = '****' . substr($Exch_API_Private, -4);
        //$UserID = $field['UserID'];
        $ExchActive = $field['ExchActive'];

        $currtime = time(); // get the Unix timestamp of now
        $fortime = date('Y-m-d H:i:s', $currtime);
        $Timestamp = $fortime;
//   dump($MasterExchID);
        //           exit;
        //  $fortime = format_date( time(), 'large'); // using the user's timezone preferences
        /*$insert = array('name' => $name, 'mobilenumber' => $number, 'email' => $email, 'age' => $age, 'gender' => $gender, 'website' => $website);
        db_insert('engdata')
        ->fields($insert)
        ->execute();

        if($insert == TRUE)
        {
        drupal_set_message("your application subimitted successfully");
        }
        else
        {
        drupal_set_message("your application not subimitted ");
        }*/
        global $base_url;

        if (isset($_GET['num'])) {
            $field = array(
                //'MasterExchID' => $_GET['num'],
                //'MasterExchName' => $MasterExchName,
                'ExchActive' => $ExchActive,
                'Timestamp' => $Timestamp,
                'UserConnName' => $UserConnName,
            );
            $query = \Drupal::database();
            $query->update('xb_exchconnection')
                ->fields($field)
                ->condition('ConnID', $_GET['num'])
                ->execute();
            drupal_set_message("succesfully updated");
            // $form_state->setRedirect('engdata.display_table_controller_display');
            // $response = new RedirectResponse(\Drupal::url('user.page'));
            $response = new RedirectResponse($base_url . '/list/exconn');
            $response->send();
        } else {
            $field = array(
                'MasterExchID' => $MasterExchID,
                'ExchActive' => $ExchActive,
                'Timestamp' => $Timestamp,
                'UserConnName' => $UserConnName,
                'Exch_API_Public' => $Exch_API_Public,
                'Exch_API_PublicVersion' => $Exch_API_PublicVersion,
                'Exch_API_Private' => $Exch_API_Public,
                'Exch_API_PrivateVersion' => $Exch_API_PrivateVersion,
                'UserID' => $uid,
            );
            $query = \Drupal::database();
            $query->insert('xb_exchconnection')
                ->fields($field)
                ->execute();
            drupal_set_message("succesfully saved");
            $response = new RedirectResponse($base_url . '/list/exconn');
            // $response = new RedirectResponse("/exchanges");
            $response->send();
            // $form_state->setRedirect('engdata.display_table_controller_display');

        }
    }

}
function _get_exchanges()
{
    // $exchange = array('- Select exchange -');
    $conn = Database::getConnection();
    $results = array();
    $query = $conn->select('xb_masterexch', 'e')
        ->condition('MasterExchActive', 1)
        ->fields('e');
    $results = $query->execute()->fetchAll();

    $exchanges = array();

    foreach ($results as $h) {
        $exchanges[$h->MasterExchID] = $h->MasterExchName;
    }
    //  $uniqueExchanges = array_unique($exchanges);

    return $exchanges;

}
