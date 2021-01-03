<?php

namespace Drupal\engdata\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class MasterExchangeForm.
 *
 * @package Drupal\engdata\Form
 */
class masterExchangeForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'masterexchange_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $currtime = time(); // get the Unix timestamp of now
   // $fortime = format_date( time(), 'Y-m-d H:i:s'); // using the user's timezone preferences
    $fortime = date('Y-m-d H:i:s', $currtime);


// Load the current user.
$user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
$uid= $user->get('uid')->value;
//  dump($user);
//  exit;

    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('xb_masterexch', 'e')
            ->condition('MasterExchID', $_GET['num'])
            ->fields('e');
        $record = $query->execute()->fetchAssoc();

    }
// dump($record);
// exit;
    $form['MasterExchName'] = array(
      '#type' => 'textfield',
      '#title' => t('Exchange Name:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['MasterExchName']) && $_GET['num']) ? $record['MasterExchName']:'',
      );
     // $default = 'Yes';

      $form['MasterExchActive'] = array (
        '#type' => 'select',
        '#title' => ('Enabled:'),
        '#required' => TRUE,
        '#options' => array(
          '1' => t('Yes'),
          '0' => t('No'),
          
       //   '#default_value' => (isset($record['MasterExchActive']) && $_GET['num']) ? $record['MasterExchActive']:'',
       //  '#default_value' =>$default,
          ),
        ); 
      
    

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
  public function validateForm(array &$form, FormStateInterface $form_state) {

        //  $name = $form_state->getValue('candidate_name');
        //   if(preg_match('/[^A-Za-z]/', $name)) {
        //      $form_state->setErrorByName('candidate_name', $this->t('your name must in characters without space'));
        //   }

          // Confirm that age is numeric.
        // if (!intval($form_state->getValue('candidate_age'))) {
        //      $form_state->setErrorByName('candidate_age', $this->t('Age needs to be a number'));
        //     }

         /* $number = $form_state->getValue('candidate_age');
          if(!preg_match('/[^A-Za-z]/', $number)) {
             $form_state->setErrorByName('candidate_age', $this->t('your age must in numbers'));
          }*/

          // if (strlen($form_state->getValue('mobile_number')) < 10 ) {
          //   $form_state->setErrorByName('mobile_number', $this->t('your mobile number must in 10 digits'));
          //  }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {


//MasterExchID, MasterExchName, MasterExchActive, Timestamp
    $field=$form_state->getValues();
    //$MasterExchID=$field['MasterExchID'];
     $MasterExchName=$field['MasterExchName'];
    $MasterExchActive=$field['MasterExchActive'];
    $currtime = time(); // get the Unix timestamp of now
    $fortime = date('Y-m-d H:i:s', $currtime);
    $Timestamp=$fortime ;
//  dump($Eng_Config_Last_Status);
//  exit;
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
          $field  = array(
              'MasterExchID'   =>  $_GET['num'],
              'MasterExchName' =>  $MasterExchName,
              'MasterExchActive' =>  $MasterExchActive,
              'Timestamp' => $Timestamp,
           
          );
          $query = \Drupal::database();
          $query->update('xb_masterexch')
              ->fields($field)
              ->condition('MasterExchID', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
         // $form_state->setRedirect('engdata.display_table_controller_display');
        // $response = new RedirectResponse(\Drupal::url('user.page'));
         $response = new RedirectResponse($base_url .'/exchanges');
           $response->send();
      }

       else
       { 
           $field  = array(
           // 'MasterExchID'   => $MasterExchID,
            'MasterExchName' =>  $MasterExchName,
            'MasterExchActive' =>  $MasterExchActive,
            'Timestamp' => $Timestamp,
          );
           $query = \Drupal::database();
           $query ->insert('xb_masterexch')
               ->fields($field)
               ->execute();
           drupal_set_message("succesfully saved");
           $response = new RedirectResponse($base_url .'/exchanges');
          // $response = new RedirectResponse("/exchanges");
           $response->send();
         // $form_state->setRedirect('engdata.display_table_controller_display');

       }
     }

}
