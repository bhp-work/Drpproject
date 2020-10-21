<?php

namespace Drupal\simple_form\form;    
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/*
Simple Form
*/
class SimpleForm extends FormBase{


public function getFormId()
{

    return 'simple_form';
}

public function buildForm(array $form,FormStateInterface $form_state )
{
$form['number_1']=[
    '#type'=> 'textfield',
    '#title'=> $this->t('First Number'),
];
$form['number_2']=[

    '#type'=> 'textfield',
    '#title'=> $this->t('Second Number'),
];

$form['submit']=[
    '#type'=>'submit',
    '#value'=>$this->t('calculate'),
];
return $form;

}


public function submitform(array &$form,FormStateInterface $form_state)
{
drupal_set_message($form_state->getValue('number_1')+ $form_state->getValue('number_2'));
}


}