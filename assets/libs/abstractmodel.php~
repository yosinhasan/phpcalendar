<?php
/**
 * @author Yosin Hasan
 * User: phpstudent
 * Date: 5.08.15
 * Time: 23:19
 * @version: 0.0.3
 */
abstract class AbstractModel
{
    protected  $properties = array();
    protected  $table_name = null;
    protected  static  $db = null;
    public  $id = null;

    /**
     * set table_name
     * 
     * @param $table_name
     */
    public function __construct($table_name)
    {
        $this->table_name = $table_name;
    }

    /**
     * set database
     * 
     * @param $db
     */
    public static function setDB($db)
    {
        self::$db = $db;
    }

    /**
     * get id
     * 
     * @return mixed
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * check whether data is saved
     * 
     * @return bool
     */
    public function checkIsSaved()
    {
        return $this->getID() > 0;
    }

    /**
     * load data from database for special id
     * 
     * @param $id
     * @return bool
     */
    public function load($id)
    {
        $fields = array_keys($this->properties);
        array_push($fields, "id");
        $select = new SelectPDO();
        $select->from($this->table_name, $fields)
            ->where("`id`=".self::$db->getSq(), array($id));
        $row = self::$db->selectRow($select);
        if(!$row) {
            return false;
        } else {
            return $this->init($row);    
        }
        
    }
    /**
     * initialize accepted data into object
     * 
     * @param $row
     * @return bool
     */
    public function init($row)
    {
        if ((count($row) > 0)) {
            foreach ($this->properties as $key =>  $value) {
                $this->properties[$key]["value"] = $row[$key];
            }
            $this->id = $row["id"];
        }
        return true;
    }

    /**
     * save data into database, it can be either insert or update
     * 
     * @return bool
     */
    public function save()
    {

        $row = array();
        foreach ($this->properties as $key => $value) {
            $row[$key] = $value["value"];

        }
        if (($this->checkIsSaved())) {
            $success = self::$db->update($this->table_name, $row, "`id`=".self::$db->getSq(), array($this->getID()));
            if ($success) {
                return true;
            }
        } else {
            $this->id = self::$db->insert($this->table_name, $row);
            if ($this->id) {
                return true;
            }
        }

        return false;
    }
    /**
     * delete data of special user id from database
     * 
     * @return bool
     */
    public function delete()
    {
		if (!$this->checkIsSaved()) return false;
		$success = self::$db->delete($this->table_name, "`id` = ".self::$db->getSQ(), array($this->getID()));
		if (!$success) {
            return false;
        } else {
            return true;    
        }
	}
      /**
     * get data from table for special field
     * 
     * @param $table
     * @param $field
     * @param $value
     * @return mixed
     */
    public static function getByField($class, $table, $field, $value)
    {
        $select = new SelectPDO();
        $select->from($table, "*")
               ->where("`$field` = ? ", array($value));
        $data = self::$db->selectRow($select);
       
        return AbstractModel::createObject($class, $data);
     }

    /**
     * add table's field into array properties, then it can be used to be handled
     * 
     * @param $name
     * @param $value
     */
    public function addField($name, $value)
    {
        $this->properties[$name]["value"] = $value;

    }

    
    /**
     * convert accepted array data into object of class model
     * 
     * @param $data
     * @return array|bool
     */
    public static function  createObject($class, $data)
    {
        $test = new $class();
        if (!($test instanceof AbstractModel)) {
            return false;
        }
        $model = new $class();
        $model->init($data);
        return $model;
    }

    /**
     * get table's fields from properties
     * 
     * @return array
     */
    public function getFields()
    {
        return array_keys($this->properties);
    }

   
	 

    /**
     * get special property from properties
     * 
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if ((array_key_exists($name, $this->properties))) return $this->properties[$name]["value"];

    }

    /**
     * set value for special property in array properties
     * 
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if ((array_key_exists($name, $this->properties))) $this->properties[$name]["value"] = $value;
    }

    
     
     /**
     * get all data from table by where, order and limit
     * 
     * @param $table
     * @param bool|false $where
     * @param $params
     * @param bool|false $order
     * @param bool|false $desc
     * @param bool|false $limit
     * @param string $offset
     * @return array|bool
     */
    public static function getWithAllParams($table, $fields = false, $where = false, $params, $order = false, $desc = false,
                                            $limit = false, $offset = false)
    {

        $select = new SelectPDO();
        if ($fields) {
          $select->from($table, $fields);    
        } else {
          $select->from($table, "*");    
        }
        
        if ($where) {
            $select->where($where, $params);
        }
        if ($order) {
            $select->order($order, $desc);
        }
        if ($limit) {
            $select->limit($limit, $offset);
        }
      //  exit($select->query());
        $data = self::$db->select($select);
        if ($data) {
            return $data;    
        } else {
            return false;    
        }
        
    }
    
      
    /**
     * get all data from table by where
     * 
     * @param $table
     * @param bool|false $where
     * @param $params
     * @return array|bool
     */
    public static function getOnWhere($table, $fields = false, $where = false, $params)
    {
        return AbstractModel::getWithAllParams($table, $fields, $where, $params, false, false, false, false);
    }
    /**
     * get all data from table
     * 
     * @param $table
     * @param bool|false $where
     * @param $params
     * @return array|bool
     */
    public static function getAll($table, $fields = false)
    {
        return AbstractModel::getWithAllParams($table, $fields, false, false, false, false, false, false);
    }
    /**
     * update fields of table where  @param $field  equal to @param $value
     * 
     * @param type $table
     * @param type $fields
     * @param type $field
     * @param type $value
     * @return boolean
     * @throws Exception
     */
    public static function updateOnField($table, $fields, $field, $value)
    {
        if (self::$db->update($table, $fields, " `$field` = ? ", array($value))) {
            return true;
        } else {
            throw new Exception("FUNCTION CAN NOT UPDATE DATA");
        }
    }    
    

}

