<?php

namespace Drupal\engdata\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\encrypt\Entity\EncryptionProfile;

/**
 * Class exchangeapiform.
 *
 * @package Drupal\engdata\Form
 */
class exchangeapiform_old extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'exchangeapiform_form';
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
            '#prefix' => '<h4>Exchange API config list:</h4> </hr> ',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('You have 0 exchange API config'),
        ];

        $form['header_config'] = [
            '#type' => 'item',
            // '#title' => t('Configure exchange connections:'),
            '#markup' => '<h4>Add new exchange API Keys:</h4> </hr>',
        ];

        // Define a wrapper id to populate new content into.
        $ajax_wrapper = 'my-ajax-wrapper';

        // $exch= \Drupal::service('engdata.exchnagemanager')->get_all_exchanges();
        // Sector.
        $form['exchange'] = [
            '#type' => 'select',
            '#empty_value' => '',
            '#required' => true,
            '#empty_option' => '- Select -',
            '#default_value' => (isset($values['exchange']) ? $values['exchange'] : ''),
            '#options' => _get_all_exchanges(),
            '#ajax' => [
                'callback' => [$this, 'exchangeChange'],
                'event' => 'change',
                'wrapper' => $ajax_wrapper,
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('fetching contents...'),
                ],
            ],

        ];

        // Build a wrapper for the ajax response.
        $form['my_ajax_container'] = [
            '#type' => 'container',
            '#prefix' => '<div>',
            '#suffix' => '</br></div>',
            '#attributes' => [
                'id' => $ajax_wrapper,
            ],
        ];
        // $form['field_hdd_serial_no']['#prefix'] = '<div id="ajax_wrapper">';
        // $form['field_hdd_serial_no']['#suffix'] = '</div>';

        // ONLY LOADED IN AJAX RESPONSE OR IF FORM STATE VALUES POPULATED.
        if (!empty($values) && !empty($values['exchange'])) {
            $options = _load_data($values['exchange']);

            $form['my_ajax_container']['connection_name'] = array(
                '#type' => 'textfield',
                '#title' => t('Connection Name:'),
                '#required' => true,
                // '#default_value' => (isset($options[0]->connection_name)) ? $options[0]->connection_name : '',
            );

            $form['my_ajax_container']['api_key'] = array(
                '#type' => 'textfield',
                '#title' => t('Api Key'),
                '#size' => '64',

                //  '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
                // '#default_value' => (isset($options[0]->api_key)) ? $options[0]->api_key : '',

            );
            $form['my_ajax_container']['api_s_key'] = array(
                '#type' => 'textfield',
                '#title' => t('Api Secret Key'),
                '#size' => '64',
                // '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
                //    '#default_value' => (isset($options[0]->api_s_key)) ? $options[0]->api_s_key : '',

            );

            $form['my_ajax_container']['user_ex_setting_id'] = array(
                '#type' => 'hidden',
                // '#title' => t('Api Secret Key'),
                // '#attributes' => array('readonly' => 'readonly','disabled'=>'TRUE'),
                //'#default_value' => (isset($options[0]->user_ex_setting_id)) ? $options[0]->user_ex_setting_id : '',

            );
            $form['my_ajax_container']['ticker_rate'] = array(
                '#type' => 'select',
                '#empty_value' => '',
                '#title' => t('Ticker rate:'),
                //'#required' => true,
                '#empty_option' => '- Select -',
                '#options' => array(
                    'Highest bid' => t('Highest bid'),
                    'Lowest ask' => t('Lowest ask')),
                //   '#default_value' => (isset($values['ticker_rate']) ? $values['ticker_rate'] : ''),
            );
            // Base currency    dropdown
            // Allowed coins    Checkboxe list
            // Percentage by amount    text-numeric
            // minimum currency amount per order    text-numeric
            // maximum currency amount allocated    text-numeric
            $form['my_ajax_container']['Per_by_amt'] = array(
                //  '#type' => 'textfield',
                '#title' => t('Percentage by amount:'),
                '#type' => 'number',
                '#min' => 0,
                '#step' => 0.01,
                //  '#required' => true,
                // '#default_value' => (isset($options[0]->connection_name)) ? $options[0]->connection_name : '',
                '#default_value' => '0.0',
            );
            $form['my_ajax_container']['min_cur_amt'] = array(
                //   '#type' => 'textfield',
                '#title' => t('minimum currency amount per order:'),
                //  '#required' => true,
                // '#default_value' => (isset($options[0]->connection_name)) ? $options[0]->connection_name : '',
                '#default_value' => '0.0',
                '#type' => 'number',
                '#min' => 0,
                '#step' => 0.01,
            );
            $form['my_ajax_container']['max_cur_amt'] = array(
                //  '#type' => 'textfield',
                '#title' => t('maximum currency amount allocated:'),
                // '#required' => true,
                // '#default_value' => (isset($options[0]->connection_name)) ? $options[0]->connection_name : '',
                '#default_value' => '0.0',
                '#type' => 'number',
                '#min' => 0,
                '#step' => 0.01,
            );
        }

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => 'save',
            //'#value' => t('Submit'),
        ];

        return $form;
    }

    /**
     * The callback function for when the `exchange` element is changed.
     *
     * What this returns will be replace the wrapper provided.
     */
    public function exchangeChange(array $form, FormStateInterface $form_state)
    {
        // // Return the element that will replace the wrapper (we return itself).
        // if ($selectedValue = $form_state->getValue('exchange')) {
        //     // Get the text of the selected option.
        //     $selectedText = $form['exchange']['#options'][$selectedValue];

        if ($selectedValue = $form_state->getValue('exchange')) {
            $options = _load_data($selectedValue);

            $form['my_ajax_container']['connection_name']['#value'] = $options[0]->connection_name;
            $form['my_ajax_container']['api_key']['#value'] = $options[0]->api_key;
            $form['my_ajax_container']['api_s_key']['#value'] = $options[0]->api_s_key;
            $form['my_ajax_container']['user_ex_setting_id']['#value'] = $options[0]->user_ex_setting_id;
        } else {

            $form['my_ajax_container']['connection_name']['#value'] = 1;
            $form['my_ajax_container']['api_key']['#value'] = 2;
            $form['my_ajax_container']['api_s_key']['#value'] = 3;
            $form['my_ajax_container']['user_ex_setting_id']['#value'] = 4;
        }
        return $form['my_ajax_container'];

        // $commands[] = ajax_command_replace('#ajax_wrapper', drupal_render($form['my_ajax_container']));
        // return array('#type' => 'ajax','#commands' => $commands);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {$field = $form_state->getValues();
        $api_key = "";
        $api_s_key = "";
        if (!empty($field)) {
            $api_key = $field['api_key'];
            $api_s_key = $field['api_s_key'];
        }
        parent::validateForm($form, $form_state);
        //    $name = $form_state->getValue('candidate_name');

        if (strlen($api_key) != 64) {
            $form_state->setErrorByName('api_key', $this->t('Please enter valid API key ' . strlen($api_key)));
        }
        if (!ctype_alnum($api_key)) {
            $form_state->setErrorByName('api_key', $this->t('API key must not have space or alphanumeric characters'));
        }

        if (strlen($api_s_key) != 64) {
            $form_state->setErrorByName('api_s_key', $this->t('Please enter valid API secret key' . strlen($api_s_key)));
        }
        if (!ctype_alnum($api_s_key)) {
            $form_state->setErrorByName('api_s_key', $this->t('API secret key must not have space or alphanumeric characters'));
        }

    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {

        $string = "This is test api key!";
        $encryptProfile = EncryptionProfile::load('encrypt-profile');
        $encrypted = \Drupal::service('encryption')->encrypt($string, $encryptProfile);
        //  echo $encrypted;
        // $encrypted = \Drupal::service('encryption')->encrypt($string, $encryption_profile);
        // $decrypted = \Drupal::service('encryption')->decrypt($encrypted, $encryption_profile);

        // dsm($encrypted);
        // dsm($decrypted);
        // Load the current user.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $uid = $user->get('uid')->value;
        $currtime = time(); // get the Unix timestamp of now
        $fortime = date('Y-m-d H:i:s', $currtime);

        $field = $form_state->getValues();
        // $user_ex_setting_id=$field['user_ex_setting_id'];
        // $User_ID=$field['User_ID'];
        $connection_name = $field['connection_name'];
        $exchange = $field['exchange'];
        $api_key = $field['api_key'];
        $api_s_key = $field['api_s_key'];
        $user_ex_setting_id = $field['user_ex_setting_id'];

        $currtime = time(); // get the Unix timestamp of now
        $fortime = date('Y-m-d H:i:s', $currtime);

        // if (!empty($user_ex_setting_id)) {
        //     $field = array(
        //         'connection_name' => $connection_name,
        //         'exchange' => $exchange,
        //         'api_key' => $api_key,
        //         'api_s_key' => $api_s_key,
        //         'last_updated' => $fortime,
        //     );
        //     $query = \Drupal::database();
        //     $query->update('user_ex_setting')
        //         ->fields($field)
        //         ->condition('user_ex_setting_id', $user_ex_setting_id)
        //         ->execute();
        //     drupal_set_message("succesfully updated");
        //     //   $form_state->setRedirect('engdata.display_table_controller_display');

        // } else {

            $field = array(
                'user_id' => $uid,
                'connection_name' => $connection_name,
                'exchange' => $exchange,
                'api_key' => $api_key,
                'api_s_key' => $api_s_key,
                'last_updated' => $fortime,
            );
            $query = \Drupal::database();
            $query->insert('user_ex_setting')
                ->fields($field)
                ->execute();

            drupal_set_message("succesfully saved");

            //  $response = new RedirectResponse("/engdata/manage/table");
            //  $response->send();
            //  $form_state->setRedirect('engdata.display_table_controller_display');

        //}
    }

}
// /**
//  * Function for populating exchange
//  */
// function _load_exchanges()
// {
//     $exchange = array('- Select exchange -');
//     $conn = Database::getConnection();
//     $results = array();
//     $query = $conn->select('user_pair_connection', 'e')
//         ->condition('user_id', -1)
//         ->condition('enabled', 1)
//         ->fields('e');
//     $results = $query->execute()->fetchAll();

//     $exchanges = array();

//     foreach ($results as $h) {
//         $exchanges[$h->exchange] = $h->exchange;
//     }
//     $uniqueExchanges = array_unique($exchanges);

//     return $uniqueExchanges;
// }
function _load_data($exchange)
{
    // dump($exchange);
    // dump($form_state['values']['exchange']);
    // Load the current user.
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $uid = $user->get('uid')->value;
    $result = array();

    // Select table
    $query = db_select("user_ex_setting", "u");
    // Selected fields
    $query->fields("u");
    // Filter the active ones only
    $query->condition('u.user_id', $uid);
    // Filter based exchange
    $query->condition("u.exchange", $exchange);
    // Order by name
    // $query->orderBy("u.pair");
    // Execute query
    $connection = $query->execute()->fetchAll();
    // $result = $query->execute();
    // // $results = $query->execute()->fetchAll();
    // // dump($resultsss);
    // // exit;
    // $connection = array();
    // while ($row = $result->fetchObject()) {
    //     // Key-value pair for dropdown options
    //     $connection[$row->connection_name] = $row->connection_name;
    // }
    //  dump($result);
    //   exit;

    return $connection;
}
// function _load_pairs($exchange)
// {
//   $pair = array();

//   // Select table
//   $query = db_select("user_pair_connection", "u");
//   // Selected fields
//   $query->fields("u");
//   // Filter the active ones only
//   $query->condition("u.enabled", 1)
//       ->condition('u.user_id', -1);
//   // Filter based exchange
//   $query->condition("u.exchange", $exchange);
//   // Order by name
//   $query->orderBy("u.pair");
//   // Execute query
//   $result = $query->execute();
//   // $results = $query->execute()->fetchAll();
//   // dump($resultsss);
//   // exit;
//   while ($row = $result->fetchObject()) {
//       // Key-value pair for dropdown options
//       $pair[$row->pair] = $row->pair;
//   }

//   return $pair;
// }


    /**
     * Function for populating exchange
     */
 function _get_all_exchanges()
    {
            $exchange = array('- Select exchange -');
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