<?php

namespace Drupal\engdata\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class pairconnform.
 *
 * @package Drupal\engdata\Form
 */
class pairconnform extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pairconnform_form';
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
        $query = $conn->select('user_pair_connection', 'e')
            ->condition('connection_id', $_GET['num'])
            ->fields('e');
        $record = $query->execute()->fetchAssoc();

    }
// dump($record);
// exit;
    $form['connection_id'] = array (
      '#type' => 'textfield',
      '#title' => t('Connection ID:'),
      '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
      '#default_value' => (isset($record['connection_id']) && $_GET['num']) ? $record['connection_id']:'',
      '#attributes' => array('readonly' => 'readonly'),
       );
       $form['user_id'] = array (
        '#type' => 'textfield',
        '#title' => t('User ID:'),
        '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
        '#default_value' => (isset($record['user_id']) && $_GET['num']) ? $record['user_id']:$uid,
         );
 

    $form['exchange'] = array(
      '#type' => 'textfield',
      '#title' => t('Exchange:'),
      '#required' => TRUE,
      '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
      '#default_value' => (isset($record['exchange']) && $_GET['num']) ? $record['exchange']:'',
      );
     // $default = 'Yes';

      $form['enabled'] = array (
        '#type' => 'select',
        '#title' => ('Enabled:'),
        '#required' => TRUE,
        '#options' => array(
          '1' => t('Yes'),
          '0' => t('No'),
          
       //   '#default_value' => (isset($record['Eng_Config_Enabled']) && $_GET['num']) ? $record['Eng_Config_Enabled']:'',
       //  '#default_value' =>$default,
          ),
        ); 
          

    $form['last_updated'] = array (
      '#type' => 'textfield',
      '#title' => t('Last Update'),
      '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
      '#default_value' => (isset($record['last_updated']) && $_GET['num']) ? $record['last_updated']:'',
     // '#default_value' => $fortime,
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

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    //connection_id, user_id, exchange, pair, enabled, last_updated
    $field=$form_state->getValues();
    $connection_id=$field['connection_id'];
    //echo "$name";
    $user_id=$field['user_id'];
    $exchange=$field['exchange'];
    $pair=$field['pair'];
    $enabled=$field['enabled'];
   

   // connection_id, user_id, exchange, pair, enabled, last_updated
    if (isset($_GET['num'])) {
          $field  = array(
              
              'enabled' =>  $enabled,
               'last_updated' => $fortime,
          );
          $query = \Drupal::database();
          $query->update('user_pair_connection')
              ->fields($field)
              ->condition('connection_id', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
          $form_state->setRedirect('engdata.display_Pair_Connection_controller');

      }

       else
       { 
           $field  = array(
            'user_id' =>$user_id,
            'exchange'=>$exchange,            
            'pair'   => $pair,
              'enabled' =>  $enabled,
              'last_updated' => $fortime,
          );
           $query = \Drupal::database();
           $query ->insert('user_pair_connection')
               ->fields($field)
               ->execute();
           drupal_set_message("succesfully saved");

     
          $form_state->setRedirect('engdata.display_Pair_Connection_controller');

       }
     }

}
