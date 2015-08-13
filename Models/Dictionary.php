<?php
/**
 * Created by PhpStorm.
 * User: Khoa
 * Date: 8/10/2015
 * Time: 11:28 PM
 */

namespace Models;


use Database\Database;

class Dictionary {
    private $db;
    private $words;
    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    /**
     * @throws \Exception
     */
    public function setDict()
    {
        $words = array();
        $this->db->connect();
        $results = $this->db->select('gcide', 'w');
        foreach($results as $row){
            $words[] = $row['w'];
        }
        $this->words = $words;
    }

    /**
     * @return mixed
     */
    public function getDict()
    {
        return $this->words;
    }

}