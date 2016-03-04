<?php
/**
 * @author Yosin Hasan
 * User: developer
 * Date: 5.09.15
 * Time: 17:02
 * @version: 0.0.1
 */
class Request {
    private $data = array();

    public function __construct(){
        $this->data = $this->handle($_REQUEST);

    }
    /**
     * get data from array data
     * @return data
     */
    public function __get($key) {
        if (array_key_exists($key,$this->data)) {
            return $this->data[$key];
        }
    }
    /**
     * handling data
     * @return $r
     */
    private function handle($arr){
        $r = array();
        foreach($arr as $k => $v){
            $r[$k] = htmlspecialchars($v);
        }
        return $r;
    }
    /**
     * check whether method is get
     * @return boolean
     */
    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    /**
     * check whether method is post
     * @return boolean
     */
    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}
