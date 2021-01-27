<?php

namespace Drupal\engdata\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
/**
 * Class DeleteUserExConn.
 *
 * @package Drupal\engdata\Form
 */
class DeleteUserExConn extends ConfirmFormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'deleteuserexConn_form';
  }

  public $cid;

  public function getQuestion() { 
    return t('Do you want to delete %cid?', array('%cid' => $this->cid));
  }

  public function getCancelUrl() {
    return new Url('engdata.user_connection_controller');
}
public function getDescription() {
    return t('All the details related to this connection will be deleted. Are you sure you want to delete it?');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete it!');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL) {

     $this->id = $cid;
    return parent::buildForm($form, $form_state);
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
    // $num=$form_state->getValues('id');
    // echo "$num";
    // $name=$field['id'];
    // echo "$name";
    // die;

    //print_r($form_state);die;
   $query = \Drupal::database();
    //echo $this->id; die;
    $query->delete('xb_exchconnection')
        //->fields($field)
          ->condition('ConnID',$this->id)
        ->execute();
        if($query == TRUE){
             drupal_set_message("succesfully deleted");
            }
         else{

           drupal_set_message("Error occured while deleting");

         }
   // $form_state->setRedirect('/list/masterexchange');
    $form_state->setRedirect('engdata.user_connection_controller');
  }


  //   $num_deleted = db_delete('engdata')
  // ->condition('id', 1)
  // ->execute();

  //     if($num_deleted == TRUE){
  //        drupal_set_message("deleted suceesfully");
  //      }
  //    else
  //     {

  //       drupal_set_message(" unsucessfully");
  //      }
  
  // }

  

}
