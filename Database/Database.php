<?php
/**
 * Created by PhpStorm.
 * User: Khoa
 * Date: 8/10/2015
 * Time: 10:52 PM
 */

namespace Database;


class Database {
    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_pass = 'root';
    private $db_name = 'contemi';

    private $conn = false;

    public function __construct($params = null){
        if(is_array($params)){
            foreach($params as $key => $val){
                $this->$key = $val;
            }
        }
    }
    /**
     * Connect DB
     *
     * @return bool
     */
    public function connect()
    {
        if(!$this->conn){
            $conn = @mysql_connect($this->db_host, $this->db_user, $this->db_pass);
            if($conn){
                $selDB = @mysql_select_db($this->db_name, $conn);
                if($selDB){
                    $this->conn = true;
                    return true;
                } else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    /**
     * @return bool
     */
    public function disconnect()
    {
        if($this->conn){
            if(@mysql_close()){
                $this->conn = false;
                return true;
            }else{
                return false;
            }
        }
        return true;
    }

    /**
     * @param $table
     * @param string $rows
     * @param null $where
     * @param null $order
     * @return bool
     */
    public function select($table, $rows = '*', $where = null, $order = null)
    {
        $q = 'SELECT '.$rows.' FROM '.$table;
        if($where != null){
            $q .= ' WHERE '.$where;
        }
        if($order != null){
            $q .= ' ORDER BY '.$order;
        }
        if($this->tableExists($table)){
            $query = @mysql_query($q);
            if($query){
                $numResults = mysql_num_rows($query);
                $results = array();
                for($i = 0; $i < $numResults; $i++){
                    $r = mysql_fetch_array($query);
                    $key = array_keys($r);
                    for($x = 0; $x < count($key); $x++){
                        // Sanitizes keys so only alpha values are allowed
                        if(!is_int($key[$x])){
                            if(mysql_num_rows($query) > 1){
                                $results[$i][$key[$x]] = $r[$key[$x]];
                            }else if(mysql_num_rows($query) < 1){
                                $results = null;
                            }else{
                                $results[$key[$x]] = $r[$key[$x]];
                            }
                        }
                    }
                }
                return $results;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function count($table, $rows = '*', $where = null)
    {
        $q = 'SELECT '.$rows.' FROM '.$table;
        if($where != null){
            $q .= ' WHERE '.$where;
        }

        if($this->tableExists($table)){
            $query = @mysql_query($q);
            if($query){
                $numResults = mysql_num_rows($query);
                return $numResults;
            }else{
                throw new \Exception('Source not found.');
            }
        }else{
            throw new \Exception('Table does not exist.');
        }
    }

    /**
     * @param $table
     * @param $values
     * @param null $rows
     * @return bool
     */
    public function insert($table, $values, $rows = null)
    {
        if($this->tableExists($table))
        {
            $insert = 'INSERT INTO '.$table;
            if($rows != null)
            {
                $insert .= ' ('.$rows.')';
            }
            for($i = 0; $i < count($values); $i++)
            {
                if(is_string($values[$i]))
                    $values[$i] = '"'.$values[$i].'"';
            }
            $values = implode(',',$values);
            $insert .= ' VALUES ('.$values.')';
            $ins = @mysql_query($insert);
            if($ins)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $table
     * @param $rows
     * @param $where
     * @return bool
     */
    public function update($table, $rows, $where)
    {
        if($this->tableExists($table))
        {
            // Parse the where values
            // even values (including 0) contain the where rows
            // odd values contain the clauses for the row
            for($i = 0; $i < count($where); $i++)
            {
                if($i%2 != 0)
                {
                    if(is_string($where[$i]))
                    {
                        if(($i+1) != null)
                            $where[$i] = '"'.$where[$i].'" AND ';
                        else
                            $where[$i] = '"'.$where[$i].'"';
                    }
                }
            }
            $where = implode('=',$where);
            $update = 'UPDATE '.$table.' SET ';
            $keys = array_keys($rows);
            for($i = 0; $i < count($rows); $i++)
            {
                if(is_string($rows[$keys[$i]]))
                {
                    $update .= $keys[$i].'="'.$rows[$keys[$i]].'"';
                }
                else
                {
                    $update .= $keys[$i].'='.$rows[$keys[$i]];
                }
                // Parse to add commas
                if($i != count($rows)-1)
                {
                    $update .= ',';
                }
            }
            $update .= ' WHERE '.$where;
            $query = @mysql_query($update);
            if($query)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * @param $table
     * @param null $where
     * @return bool
     */
    public function delete($table,$where = null)
    {
        if($this->tableExists($table))
        {
            if($where == null)
            {
                $delete = 'DELETE '.$table;
            }
            else
            {
                $delete = 'DELETE FROM '.$table.' WHERE '.$where;
            }
            $del = @mysql_query($delete);
            if($del)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * @param $table
     * @return bool
     */
    private function tableExists($table){
        $tablesInDb = @mysql_query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
        if($tablesInDb){
            if(mysql_num_rows($tablesInDb)==1){
                return true;
            }
        }
        return false;
    }
}