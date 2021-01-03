<?php

namespace Drupal\engdata\Form;



//namespace Drupal\ajaxfilters\Form;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class exchangeapiform.
 *
 * @package Drupal\engdata\Form
 */
class ajaxexample extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'ajaxexample_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        // Get the form values and raw input (unvalidated values).
        $values = $form_state->getValues();
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $uid = $user->get('uid')->value;

        // Show active connection list
        //create table header
        $header_table = array(
            'exchange' => t('Exchange'),
            'connection_name' => t('Connection'),
        );

        $query = \Drupal::database()->select('user_ex_setting', 'e');
        $query->condition('user_id', $uid);
        $query->fields('e');
        //$query->orderBy("e.pair");
        // $query->orderBy(array('e.exchange','e.pair'));
        $results = $query->execute()->fetchAll();
        $rows = array();
        foreach ($results as $data) {
            //print the data from table
            $rows[] = array(
                'Exchange' => $data->exchange,
                'Connection Name' => $data->connection_name,
            );
        }
        //display data in site
        $form['table'] = [
            '#type' => 'table',
            '#prefix' => '<h4>Exchange API configs:</h4> </hr> ',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('You have 0 exchange API config'),
        ];

        $form['header_config'] = [
            '#type' => 'item',
            // '#title' => t('Configure exchange connections:'),
            '#markup' => '<h4>Configure exchange API configuration:</h4> </hr>',
        ];


        // Create a select field that will update the contents
        // of the textbox below.
        $form['example_select'] = [
            '#type' => 'select',
            '#empty_value' => '',
            '#required' => true,
            '#empty_option' => '- Select a value -',
            '#title' => $this->t('Exchange:'),
            '#default_value' => (isset($values['exchange']) ? $values['exchange'] : ''),
            '#options' => _load_exchange_API(),
          
            '#ajax' => [
                'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
                //'callback' => [$this, 'myAjaxCallback'], //alternative notation
                'disable-refocus' => false, // Or TRUE to prevent re-focusing on the triggering element.
                'event' => 'change',
                'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Fetching contents..'),
                ],
            ],
        ];
// Build a wrapper for the ajax response.
$form['my_ajax_container'] = [
    '#type' => 'container',
    '#prefix' => '<div>',
    '#suffix' => '</div></br>',
    '#attributes' => [
        'id' => 'edit-output',
    ],
];
        // // Create a select field that will update the contents
        // // of the textbox below.
        // $form['example_select'] = [
        //   '#type' => 'select',
        //   '#title' => $this->t('Select element'),
        //   '#options' => [
        //     '1' => $this->t('One'),
        //     '2' => $this->t('Two'),
        //     '3' => $this->t('Three'),
        //     '4' => $this->t('From New York to Ger-ma-ny!'),
        //   ],
        // ];

        // Create a textbox that will be updated
        // when the user selects an item from the select box above.
        $form['output']['1'] = [
            '#type' => 'textfield',
            '#size' => '60',
            '#disabled' => true,
            '#value' => 'Hello, Drupal!!1',
            '#prefix' => '<div id="edit-output">',
            '#suffix' => '</div>',
        ];
        $form['output']['2'] = [
            '#type' => 'textfield',
            '#size' => '60',
            '#disabled' => true,
            '#value' => 'Hello, Drupal!!2',
            '#prefix' => '<div id="edit-output">',
            '#suffix' => '</div>',
        ];

        // Create the submit button.
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
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
        // Display result.
        foreach ($form_state->getValues() as $key => $value) {
            \Drupal::messenger()->addStatus($key . ': ' . $value);
        }
    }
    // Get the value from example select field and fill
    // the textbox with the selected text.
    public function myAjaxCallback(array &$form, FormStateInterface $form_state)
    {
        // Prepare our textfield. check if the example select field has a selected option.
        if ($selectedValue = $form_state->getValue('example_select')) {
            // Get the text of the selected option.
            $selectedText = $form['example_select']['#options'][$selectedValue];
            // Place the text of the selected option in our textfield.
            $form['output']['1']['#value'] = $selectedText;
            $form['output']['2']['#value'] = $selectedText;
        }
        // Return the prepared textfield.
        return $form['output'];
    }
}
/**
 * Function for populating exchange
 */
function _load_exchange_API()
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