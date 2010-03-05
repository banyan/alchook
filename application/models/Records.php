<?php

require_once APPLICATION_PATH . '/models/Abstract.php';

class Application_Model_Records extends Application_Model_Abstract
{
    CONST COLLECTION_NAME = 'records';

    private $_collection;

    public function __construct()
    {
        parent::__construct();
        $this->_collection = $this->_db->selectCollection(self::COLLECTION_NAME);
    }

    /**
     * Fetch data by identity
     *
     * @param string $identity
     * @return array
     */
    public function getByIdentity($identity)
    {
        $cursor = $this->_collection->find(array("identity" => $identity));
        $rows = array();
        foreach ($cursor as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * Return data for graph
     *
     * @param string $identity
     * @return array
     */
    public function getGraphData($identity)
    {
        $rows = $this->getByIdentity($identity);
        $hash = array();
        foreach ($rows as $row) {
            $key = date("Y-m-d", ($row['created_at']));
            if (!array_get($hash, $key)) {
                $hash[$key] = 1;
            } else {
                $hash[$key] = $hash[$key] + 1;
            }
        }

        $ret = array();
        foreach ($hash as $key => $value) {
            $buf = array();
            $buf[] = (int) strtotime($key) * 1000;
            $buf[] = (int) $value;
            $ret[] = $buf;
        }
        return $ret;
    }

    /**
     * Create records
     *
     * @param  string $query
     * @param  string $auth
     * @return void
     */
    public function create($query, $auth)
    {
        $doc = array(
           "identity"   => $auth,
           "query"      => rawurldecode($query),
           "created_at" => time(),
        );
        $this->_collection->insert($doc);
    }
}

