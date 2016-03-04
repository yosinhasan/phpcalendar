<?php
/**
 * @author Yosin Hasan
 * User: developer
 * Date: 05.09.15
 * Time: 14:56
 * @version: 0.0.1
 */

class SelectPDO extends  AbstractSelect
{
    public $params = array();
    /**
     *
     */
    public function __construct()
    {
     //   $this->table_name = $table_name;
    }
    /**
     * @param $where
     * @param array $params
     * @param bool|true $and
     * @return $this
     */
    public function where($where, $params = array(), $and = true)
    {
        if (!empty($where)) {
            if (!empty($this->where)) {
                if($and) $this->where .= " AND $where"; 
                else $this->where .= " OR $where";
                $this->params = array_merge($this->params,$params);
            } else {
                $this->where = "WHERE $where";
                $this->params = $params;
            }
        }
        return $this;
    }
    /**
     * @return string
     */
    public function query()
    {
        return "SELECT ".$this->from." FROM `".$this->table_name."` ".$this->join." ".$this->where." ".$this->group." ".$this->order." ".$this->limit;
    }
}
