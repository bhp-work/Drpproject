<?php

namespace Drupal\engdata\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class engdataForm.
 *
 * @package Drupal\engdata\Form
 */
class engdataForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'engdata_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('eng_config', 'e')
            ->condition('Eng_Config_ID', $_GET['num'])
            ->fields('e');
        $record = $query->execute()->fetchAssoc();

    }
// dump($record);
// exit;
    $form['Eng_Config_ID'] = array (
      '#type' => 'textfield',
      '#title' => t('Config ID:'),
      '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
      '#default_value' => (isset($record['Eng_Config_ID']) && $_GET['num']) ? $record['Eng_Config_ID']:'',
      '#attributes' => array('readonly' => 'readonly'),
       );
       $form['User_ID'] = array (
        '#type' => 'textfield',
        '#title' => t('User ID:'),
      //  '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
        '#default_value' => (isset($record['User_ID']) && $_GET['num']) ? $record['User_ID']:'',
         );
    $form['Eng_Config_Parent_ID'] = array(
      '#type' => 'textfield',
      '#title' => t('Parent ID:'),
      '#required' => TRUE,
     // '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
       //'#default_values' => array(array('id')),
      '#default_value' => (isset($record['Eng_Config_Parent_ID']) && $_GET['num']) ? $record['Eng_Config_Parent_ID']:'',
      );
    //print_r($form);die();

    $form['Eng_Config_Process_Name'] = array(
      '#type' => 'textfield',
      '#title' => t('Process Name:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['Eng_Config_Process_Name']) && $_GET['num']) ? $record['Eng_Config_Process_Name']:'',
      );
      $form['Eng_Config_Enabled'] = array (
        '#type' => 'select',
        '#title' => ('Enabled:'),
        '#required' => TRUE,
        '#options' => array(
          '1' => t('Yes'),
          '0' => t('No'),
          
          '#default_value' => (isset($record['Eng_Config_Enabled']) && $_GET['num']) ? $record['Eng_Config_Enabled']:'',
          ),
        ); 
        $form['Eng_Config_Run_State'] = array (
          '#type' => 'select',
          '#title' => ('Run State:'),
          '#required' => TRUE,
          '#options' => array(
            'AutoRun' => t('AutoRun'),
            'Pause' => t('Pause'),
            'Stop' => t('Stop'),
            'Pause' => t('Pause'),
            'DieNow' => t('DieNow'),
             'Restart_ Auto' => t('Restart_Autotop'),
            'Restart_Pause ' => t('Restart_Pause'),       
          //'#default_value'=>'DieNow'
            '#default_value' => (isset($record['Eng_Config_Run_State']) && $_GET['num']) ? $record['Eng_Config_Run_State']:'',
            ),
          ); 
    
    $form['Eng_Config_Options'] = array(
      '#type' => 'textfield',
      '#title' => t('Config Options:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['Eng_Config_Options']) && $_GET['num']) ? $record['Eng_Config_Options']:'',
      );

    $form['Eng_Config_Last_Status'] = array (
      '#type' => 'textfield',
      '#title' => t('Last Status:'),
     
      '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
      '#default_value' => (isset($record['Eng_Config_Last_Status']) && $_GET['num']) ? $record['Eng_Config_Last_Status']:'',
       );

   

    $form['Eng_Config_Last_Update'] = array (
      '#type' => 'textfield',
      '#title' => t('Last Update'),
      '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
      '#default_value' => (isset($record['Eng_Config_Last_Update']) && $_GET['num']) ? $record['Eng_Config_Last_Update']:'',
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


    $field=$form_state->getValues();
    $Eng_Config_ID=$field['Eng_Config_ID'];
    //echo "$name";
    $User_ID=$field['User_ID'];
    $Eng_Config_Parent_ID=$field['Eng_Config_Parent_ID'];
    $Eng_Config_Process_Name=$field['Eng_Config_Process_Name'];
    $Eng_Config_Enabled=$field['Eng_Config_Enabled'];
    $Eng_Config_Run_State=$field['Eng_Config_Run_State'];
    $Eng_Config_Options=$field['Eng_Config_Options'];
    $Eng_Config_Last_Status=$field['Eng_Config_Last_Status'];
    $Eng_Config_Last_Update=$field['Eng_Config_Last_Update'];
   
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

    if (isset($_GET['num'])) {
          $field  = array(
              'Eng_Config_Process_Name'   => $Eng_Config_Process_Name,
              'Eng_Config_Enabled' =>  $Eng_Config_Enabled,
              'Eng_Config_Run_State' =>  $Eng_Config_Run_State,
              'Eng_Config_Options' => $Eng_Config_Options,
              'Eng_Config_Last_Status' => $Eng_Config_Last_Status,
              'Eng_Config_Last_Update' => $Eng_Config_Last_Update,
          );
          $query = \Drupal::database();
          $query->update('eng_config')
              ->fields($field)
              ->condition('Eng_Config_ID', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
          $form_state->setRedirect('engdata.display_table_controller_display');

      }

       else
       { 
           $field  = array(
            'User_ID' =>$User_ID,
            'Eng_Config_Parent_ID'=>$Eng_Config_Parent_ID,            
            'Eng_Config_Process_Name'   => $Eng_Config_Process_Name,
              'Eng_Config_Enabled' =>  $Eng_Config_Enabled,
              'Eng_Config_Run_State' =>  $Eng_Config_Run_State,
              'Eng_Config_Options' => $Eng_Config_Options,
             // 'Eng_Config_Last_Status' => $Eng_Config_Last_Status,
             // 'Eng_Config_Last_Update' => $Eng_Config_Last_Update,
          );
           $query = \Drupal::database();
           $query ->insert('eng_config')
               ->fields($field)
               ->execute();
           drupal_set_message("succesfully saved");

          //  $response = new RedirectResponse("/engdata/manage/table");
          //  $response->send();
          $form_state->setRedirect('engdata.display_table_controller_display');

       }
     }

}
