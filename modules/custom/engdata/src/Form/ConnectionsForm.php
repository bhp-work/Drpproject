<?php

namespace Drupal\engdata\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConnectionsForm.
 *
 * @package Drupal\engdata\Form
 */
class connectionsform extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'connectionsform_form';
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
            'pair' => t('Pair'),

        );

        // $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        // $uid = $user->get('uid')->value;
//select records from table
        $query = \Drupal::database()->select('user_pair_connection', 'e');
        $query->condition('user_id', $uid)
            ->condition('enabled', 1);
        $query->fields('e');
        //$query->orderBy("e.pair");
        // $query->orderBy(array('e.exchange','e.pair'));
         $results = $query->execute()->fetchAll();
        $rows = array();
        foreach ($results as $data) {

            //print the data from table
            $rows[] = array(

                'Exchange' => $data->exchange,
                'Pair' => $data->pair,

            );

        }
        //display data in site
        $form['table'] = [
            '#type' => 'table',
            '#prefix' => '<h4>Active connections:</h4> </hr> ',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('You have 0 active connections'),
        ];
        $form['header_config'] = [
          '#type' => 'item',
         // '#title' => t('Configure exchange connections:'),
          '#markup' => '<h4>Configure exchange connections:</h4> </hr>',
      ];

        // Define a wrapper id to populate new content into.
        $ajax_wrapper = 'my-ajax-wrapper';

       
        // Sector.
        $form['exchange'] = [
            '#type' => 'select',
            '#empty_value' => '',
            '#required' => true,
            '#empty_option' => '- Select a value -',
            '#default_value' => (isset($values['exchange']) ? $values['exchange'] : ''),
            '#options' => _load_exchange(),
            '#ajax' => [
                'callback' => [$this, 'exchangeChange'],
                'event' => 'change',
                'wrapper' => $ajax_wrapper,
            ],
        ];

        // Build a wrapper for the ajax response.
        $form['my_ajax_container'] = [
            '#type' => 'container',
            '#prefix' => '<div>',
            '#suffix' => '</div></br>',
            '#attributes' => [
                'id' => $ajax_wrapper,
            ],
        ];

        //Get all the enabled pairs to update the enabled flag.
        $conn = Database::getConnection();
        $oldPairs = array();
        $query = $conn->select('user_pair_connection', 'e')
            ->condition('user_id', $uid)
            ->condition('enabled', 1)
            ->fields('e');
        $oldPairs = $query->execute()->fetchAll();
        $defaultPairs = array();
        foreach ($oldPairs as $i) {
            $defaultPairs = $i->pair;
        }
        // ONLY LOADED IN AJAX RESPONSE OR IF FORM STATE VALUES POPULATED.
        if (!empty($values) && !empty($values['exchange'])) {
            $options = _load_pair($values['exchange']);

            $form['my_ajax_container']['pair'] = array(
                '#title' => t('Pair'),
                '#type' => 'checkboxes',
                '#options' => $options,
                '#required' => true,
                '#default_value' => $defaultPairs,
            );
            //  [
            //   '#markup' => 'The current select value is ' . $values['exchange'],
            // ];
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
        // Return the element that will replace the wrapper (we return itself).
        return $form['my_ajax_container'];
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

// Load the current user.
        $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
        $uid = $user->get('uid')->value;
        $currtime = time(); // get the Unix timestamp of now
        $fortime = date('Y-m-d H:i:s', $currtime);

        $exchange = $form_state->getValue('exchange');
        $result = $form_state->getValue('pair');
        $selectPairs = array_filter($result);
        $notSelPairs = array_diff($result, $selectPairs);

        //Get all the enabled pairs to update the enabled flag.
        $conn = Database::getConnection();
        $oldPairs = array();
        $query = $conn->select('user_pair_connection', 'e')
            ->condition('user_id', $uid)
            ->condition('enabled', 1)
            ->condition('exchange', $exchange)
            ->fields('e');
        $oldPairs = $query->execute()->fetchAll();

        //set enabled=0 if pair is updated from enabled to disabled.
        $query = \Drupal::database();
        foreach ($oldPairs as $i) {
            $connection_id = $i->connection_id;
            dump($connection_id);
            $query->update('user_pair_connection')
                ->fields([
                    'enabled' => 0,
                    'last_updated' => $fortime,
                ])
                ->condition('connection_id', $connection_id)
                ->execute();
        }

        //Enable all the selected pairs
        foreach ($selectPairs as $sPair) {
            //INsert or Update the connections
            db_merge('user_pair_connection')
                ->key(array('exchange' => $exchange, 'pair' => $sPair, 'user_id' => $uid))
                ->fields(array(
                    'exchange' => $exchange,
                    'pair' => $sPair,
                    'user_id' => $uid,
                    'enabled' => 1,
                    'last_updated' => $fortime,
                ))
                ->execute();
        }

        drupal_set_message("succesfully saved");

    }

}
function _load_pair($exchange)
{
    $pair = array();

    // Select table
    $query = db_select("user_pair_connection", "u");
    // Selected fields
    $query->fields("u");
    // Filter the active ones only
    $query->condition("u.enabled", 1)
        ->condition('u.user_id', -1);
    // Filter based exchange
    $query->condition("u.exchange", $exchange);
    // Order by name
    $query->orderBy("u.pair");
    // Execute query
    $result = $query->execute();
    // $results = $query->execute()->fetchAll();
    // dump($resultsss);
    // exit;
    while ($row = $result->fetchObject()) {
        // Key-value pair for dropdown options
        $pair[$row->pair] = $row->pair;
    }

    return $pair;
}
/**
 * Function for populating exchange
 */
function _load_exchange()
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
