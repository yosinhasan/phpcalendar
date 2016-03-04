<?php
/**
 * @author Yosin Hasan
 * User: developer
 * Date: 05.09.15
 * Time: 16:20
 * @version: 0.0.2
 */
abstract class AbstractSelect
{
    protected $table_name = "";
    protected $from="";
    protected $join = "";
    protected $where="";
    protected $group="";
    protected $order="";
    protected $limit="";
    
    

    /**
     * @param $fields
     * @return $this
     */
    public function from($table, $fields)
    {
        $this->table_name = $table;
        $query = '';
        if (!empty($fields)) {
            if ($fields == "*") {
                $query = "*";
            } else {
                $from = '';
                if (is_array($fields)) {
                    foreach ($fields as $field) {
                        if (($pos = strpos($field, '(')) != false) {
                            $from .= substr($field, 0, $pos + 1);
                            if (($pos3 = strpos($field, ".")) != false) {
                                $from .= '`' . substr($field, $pos+1, $pos3-$pos-1) . '`';
                                $pos2 = strpos($field, ')', $pos + 1);
                                $string = substr($field, $pos3+1, $pos2 - $pos3-1);
                                $from .= ".`" . $string . "`)";
                                $from .= substr($field, $pos2 + 1) . ",";

                            } else {
                                $pos2 = strpos($field, ')', $pos + 1);
                                $string = '`'.substr($field, $pos+1, $pos2-$pos-1) . '`)';
                                $string .= substr($field, $pos2+1);
                                $from .= $string . ",";
                            }
                        } else {
                            if (($pos = strpos($field, ".")) != false) {
                                $from .= '`'.substr($field, 0, $pos).'`';
                                $string = substr($field, $pos+1);
                                if ($string == "*") {
                                    $from .='.'.$string.',';
                                } else {
                                    $from .='.`'.$string.'`,';
                                }
                            } else {
                                $from .= '`'.$field.'`,';
                            }
                        }
                    }
                    $from = substr($from, 0, -1);
                    $query = $from;
                } else {
                    if (($pos = strpos($fields, '(')) != false) {
                        $pos2 = strpos($fields, ')', $pos+1);
                        $string = substr($fields, 0, $pos+1).'`'.substr($fields, $pos+1, $pos2-$pos-1).'`)';
                        $query = $string;
                    } else {
                        $query = '`'.$fields.'`';
                    }
                }
            }
        }
        $this->from = $query;
        return $this;
    }
    /**
     * @param $limit
     * @param string $offset
     * @return $this
     */
    public function limit($limit, $offset = ""){
        $this->limit = " LIMIT ";
        if (!empty($offset)) $this->limit .= "$offset,";
        $this->limit .= "$limit";
        return $this;
    }


    /**
     * @param $order_data
     * @param bool|false $desc
     * @return $this
     */
    public function order($order_data, $desc = false, $table = false){
        if (is_array($order_data)) {
            if(!is_array($desc)) {
                $size = count($order_data);
                $temp = array();
                for ($i = 0; $i < $size; $i++) {
                    $temp[] = $desc;
                }
                $desc = $temp;
                $this->order = "ORDER BY ";
                $order = "";
                for ($i = 0; $i < $size; $i++) {
                    if ($table) $order .= "`$table`"."`$order_data[$i]`";
                    else $order .="`$this->table_name`."."`$order_data[$i]`";
                    if ($desc[$i]) { $order .= " DESC, ";
                    } else {
                        $order .= ", ";
                    }
                }
                $order = substr($order, 0, -2);
                $this->order .= $order;
            }
        } else {
            $this->order = "ORDER BY ";
            if ($table) $this->order .= "`$table`"."`$order_data`";
            else $this->order .="`$this->table_name`."."`$order_data`";
            if($desc) $this->order .=" DESC";
        }
        return $this;
    }
    /**
     * @param $table
     * @param $field
     * @param $onField
     * @return $this
     */
    public function join($table, $field, $onField)
    {
        if ((!empty($table)) && (!empty($field)) && (!empty($onField))) {
           $this->join .= "INNER JOIN `{$table}` ON `".$this->table_name."`.`".$field."` = `".$table."`.`".$onField."`   ";
        }
        return $this;
    }
     /**
     * @param $group_data
     * @return $this
     */
    public function group($group_data)
    {
        if ((!empty($group_data))) {
            if (empty($this->group)) { 
                $this->group =  "GROUP BY `$group_data`";
            } else {
                $this->group .= ", `$group_data`";
            }
        }
        return $this;
    }
    abstract public function where($where, $params = array(),$and = true);
}