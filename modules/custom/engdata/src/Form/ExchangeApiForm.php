<?php

namespace Drupal\engdata\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class exchangeapiform.
 *
 * @package Drupal\engdata\Form
 */
class exchangeapiform extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'exchangeapiform_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $currtime = time(); // get the Unix timestamp of now
      $fortime = date('Y-m-d H:i:s', $currtime);


// Load the current user.
$user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
$uid= $user->get('uid')->value;

    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('user_ex_setting', 'e')
            ->condition('user_ex_setting_id', $_GET['num'])
            ->fields('e');
        $record = $query->execute()->fetchAssoc();

    }

     
    $form['connection_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Connection Name:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['connection_name']) && $_GET['num']) ? $record['connection_name']:'',
      );
     // $default = 'Yes';

     $form['exchange'] = [
      '#type' => 'select',
      '#title' => t('Exchange:'),
      '#empty_value' => '',
      '#required' => true,
      '#empty_option' => '- Select a value -',
      '#default_value' => (isset($values['exchange']) ? $values['exchange'] : ''),
      '#options' => _load_exchanges(),
    
  ];

    $form['api_key'] = array (
      '#type' => 'textfield',
      '#title' => t('Api Key'),
    //  '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
      '#default_value' => (isset($record['api_key']) && $_GET['num']) ? $record['api_key']:'',
     
       );
       $form['api_s_key'] = array (
        '#type' => 'textfield',
        '#title' => t('Api Secret Key'),
       // '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
        '#default_value' => (isset($record['api_s_key']) && $_GET['num']) ? $record['api_s_key']:'',
       
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
    //user_ex_setting_id, user_id, connection_name, exchange, api_key, api_s_key, last_updated
// Load the current user.
$user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
$uid= $user->get('uid')->value;
    $field=$form_state->getValues();
   // $user_ex_setting_id=$field['user_ex_setting_id'];
   // $User_ID=$field['User_ID'];
    $connection_name=$field['connection_name'];
    $exchange=$field['exchange'];
    $api_key=$field['api_key'];
    $api_s_key=$field['api_s_key'];
  
    $currtime = time(); // get the Unix timestamp of now
    $fortime = date('Y-m-d H:i:s', $currtime);
 

    if (isset($_GET['num'])) {
          $field  = array(
              'connection_name'   => $connection_name,
              'exchange' =>  $exchange,
              'api_key' =>  $api_key,
              'api_s_key' => $api_s_key,
              'last_updated' => $fortime,
          );
          $query = \Drupal::database();
          $query->update('user_ex_setting')
              ->fields($field)
              ->condition('user_ex_setting_id', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
       //   $form_state->setRedirect('engdata.display_table_controller_display');

      }

       else
       { 
           $field  = array(
            'user_id' =>$uid,
            'connection_name'   => $connection_name,
            'exchange' =>  $exchange,
            'api_key' =>  $api_key,
            'api_s_key' => $api_s_key,
            'last_updated' => $fortime,
          );
           $query = \Drupal::database();
           $query ->insert('user_ex_setting')
               ->fields($field)
               ->execute();
           drupal_set_message("succesfully saved");

          //  $response = new RedirectResponse("/engdata/manage/table");
          //  $response->send();
        //  $form_state->setRedirect('engdata.display_table_controller_display');

       }
     }

}
/**
 * Function for populating exchange
 */
function _load_exchanges()
{
    $exchange = array('- Select exchange -');
    $conn = Database::getConnection();
    $results = array();
    $query = $conn->select('user_pair_connection', 'e')
        ->condition('user_id', -1)
        ->condition('enabled', 1)
        ->fields('e');
    $results = $query->execute()->fetchAll();

    $exchanges = array();

    foreach ($results as $h) {
        $exchanges[$h->exchange] = $h->exchange;
    }
    $uniqueExchanges = array_unique($exchanges);

    return $uniqueExchanges;
}
