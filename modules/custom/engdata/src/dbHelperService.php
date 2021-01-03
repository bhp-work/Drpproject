<?php

namespace Drupal\engdata;

/**
 * Class dbHelperService.
 */
class dbHelperService
{
    /*
     * @var \Drupal\Core\Database\Connection $database
     */
    protected $database;

    /**
     * Constructs a new dbHelperService object.
     * @param \Drupal\Core\Database\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->database = $connection;
    }

    /**
     * Show the author of the node.
     *
     * @param int $nid
     * The node id.
     *
     * @return int
     * Return the uid.
     */
    public function showAuthor($nid)
    {
        $query = $this->database->select('node_field_data', 'nfd');
        $query->condition('nfd.nid', $nid);
        $query->fields('nfd', ['uid']);
        $result = $query->execute()->fetchAll();
        if (!empty($result)) {
            return $result[0]->uid;
        }
    }
    /**
     * Function for populating exchange
     */
    public function get_all_exchanges()
    {
       
        $results = array();
        $query = $this->database->select('xb_masterexch', 'e')
            // ->condition('user_id', -1)
            // ->condition('enabled', 1)
            ->fields('e');
        $results = $query->execute()->fetchAll();


        return $results;
    }
}
