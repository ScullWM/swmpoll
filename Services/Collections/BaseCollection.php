<?php

namespace Services\Collections;

abstract class BaseCollection {
    
    protected $objects;
    protected $offset = 0;
    protected $limit = 10;

    public function __construct(array $datas = array())
    {
        $this->objects = $datas;
    }

    /**
     * Save a simple Object
     *
     * @version  05-12-13
     * @param Object $object
     */
    protected function add($object){
        $this->objects[] = $object;
    }

    /**
     * Get list by libelle (may include sub properties)
     *
     * @version  05-10-13
     * @param  String $libelle Property field
     * @param  String $order   ASC|DESC
     * @param  String $obj     Name of the sub object
     * @return array           Sorted list
     */
    protected function getBy($libelle, $order, $obj = null)
    {
        $list = array();
        array_map(function($obj) use (&$list, $libelle) { $list[$obj->$libelle] = $obj;}, $this->objects);
        ($order=="ASC")?ksort($list):krsort($list);

        return $this->outputList($list);
    }

    /**
     * Get list by libelle (may include sub properties)
     *
     * @todo  Find a better way to do that
     * @version  06-10-13
     * @param  String $libelle Property field
     * @param  String $order   ASC|DESC
     * @param  String $obj     Name of the sub object
     * @return array           Sorted list
     */
    protected function getByFloat($libelle, $order, $obj = null)
    {
        $list =array();
        array_map(function($obj) use (&$list, $libelle) { $list[$obj->$libelle*1000] = $obj;}, $this->objects);
        ($order=="ASC")?ksort($list):krsort($list);

        return $this->outputList($list);
    }

    /**
     * Get list by unique libelle (may include sub properties)
     *
     * @version  21-01-14
     * @param  String $libelle Property field
     * @param  String $order   ASC|DESC
     * @param  String $obj     Name of the sub object
     * @return array           Sorted list
     */
    protected function getByUnique($libelle, $order, $obj = null)
    {
        $list = $attribution =array();
        array_map(function($obj) use (&$list, $libelle, &$attribution) { 
            if(array_key_exists($obj->$libelle, $attribution)){
                $attribution[$obj->$libelle]++;
            }else{
                $attribution[$obj->$libelle] = 1;
            }
            $list[$obj->$libelle*1000+$attribution[$obj->$libelle]] = $obj;
        }, $this->objects);
        ($order=="ASC")?ksort($list):krsort($list);

        return $this->outputList($list);
    }

    /**
     * Ouput a limited size of data with offset
     *
     * @version  06-12-13
     * @param  array $datas Your computed data
     * @return array        Good serie of data
     */
    protected function outputList($datas)
    {
        return array_slice($datas, $this->offset, $this->limit);
    }

    /**
     * Output all result, no matter sort
     *
     * @version  30-12-13
     * @return array Collection objects
     */
    public function output()
    {
        return array_slice($this->objects, $this->offset, $this->limit);   
    }

    /*
    BASIC getter and setters
     */
    public function setLimit($int) { $this->limit = (int)$int; return $this; }
    public function getLimit($int) { return $this->limit; }
    public function setOffset($int) { $this->offset = (int)$int; return $this; }
    public function getOffset($int) { return $this->offset; }
    public function getNbre(){ return (int)count($this->objects); }

    protected function setDouble($i)
    {
        return (int)$i*2;
    }
}