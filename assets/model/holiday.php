<?php
/**
 * @author Yosin Hasan
 * User: developer
 * Date: 30.01.16
 * Time: 20:00
 * @version: 0.0.1
 */
class Holiday extends AbstractModel
{
    private $table = "holidays";
   
    public function __construct()
    {
        parent::__construct($this->table);
        $this->addField("name", "");
        $this->addField("date", "");
      
    }
}
