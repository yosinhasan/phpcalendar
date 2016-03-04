<?php
/**
 * @author Yosin Hasan
 * User: developer
 * Date: 5.09.15
 * Time: 14:56
 * @version: 0.0.2
 */
class DatabasePDO extends AbstractDB
{

   /**
     * @param $db_host
     * @param $db_name
     * @param $db_user
     * @param $db_password
     */
    public function __construct($db_host, $db_user, $db_password, $db_name)
    {
        $this->sq = "?";
        try {
            $this->db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    /**
     * @param SelectPDO $select
     * @return array
     */
    public function select(SelectPDO $select)
    {
        try {
            $doQuery = $this->db->prepare($select->query());
            if ($doQuery) {
                $doQuery->execute($select->params);
            }
            return $doQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param SelectPDO $select
     * @return mixed
     */
    public function selectRow(SelectPDO $select)
    {
        try {
            $doQuery = $this->db->prepare($select->query());
            if ($doQuery) {
                $doQuery->execute($select->params);
            }
            return $doQuery->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param $table_name
     * @param $fields
     * @return bool
     */
    public function insert($table_name, $fields)
    {
        if (count($fields)>0) {
            $query = "INSERT INTO `$table_name` (";
            $values = "VALUES (";
            $params = array();
            foreach($fields as $key => $value){
                $params[] = $value;
                $query .="`".$key."`,";
                $values .= $this->sq.",";
            }
            $query = substr($query, 0, -1);
            $values = substr($values, 0, -1);
            $query .= ") ";
            $values .= ")";
            $query .= $values;
            try {
                $doQuery = $this->db->prepare($query);
                if ($doQuery) {
                    $doQuery->execute($params);
                }
                return $this->db->lastInsertId();
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
         }
        return false;
    }

    /**
     * @param $table_name
     * @param $fields
     * @param $where
     * @param $params
     * @return bool
     */
    public function update($table_name, $fields, $where = false, $params = array())
    {
        if (count($fields > 0)) {
            $query = "UPDATE `$table_name` SET ";
            $params_add = array();
            foreach($fields as $key => $value){
                $query .= "`$key`= ".$this->sq.",";
                $params_add[] = $value;
            }
            $query = substr($query, 0, -1);
            if (!empty($where)) {
                $query .= " WHERE $where";
                $params = array_merge($params_add, $params);
            }
            try {
                $doQuery = $this->db->prepare($query);
                if($doQuery) {
                    $doQuery->execute($params);
                }
                return true;
            } catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        return false;
    }

    /**
     * @param $table_name
     * @param $where
     * @param array $params
     * @return bool
     */
    public function delete($table_name, $where, $params = array())
    {
        $delete = "DELETE FROM `".$table_name."`";
        if (!empty($where)) {
            $delete .= " WHERE $where";
        }
        $doQuery = $this->db->prepare($delete);
        try {
            $doQuery = $this->db->prepare($delete);
            if($doQuery){
                $doQuery->execute($params);
            }
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

   

}
