<?php 


class SelectHelper {

    
    
    /**
     * Display a select form by giving all options in array
     * 
     * @param array $data
     * @param type  $idSelected
     * @param type  $showKey
     * @return string
     */
    public static function getListByArray(array $data, $idSelected = null, $showKey = false)
    {
        $html = null;
        foreach($data as $k => $v):
            $html .= '<option value="'.$k.'"';
            if($k==$idSelected) $html .= ' selected';
            $html .= '>';
            if($showKey===true) $html .= $k.' ';
            $html .= $v.'</option>';
        endforeach;
        
        return (string) $html;        
    }


    /**
     * Display a select form by giving all objects data
     * 
     * @version 17-06-2013
     * @param array $data
     * @param string  $fieldKey
     * @param string  $fieldValue
     * @param type  $idSelected
     * @return string
     */
    public function getListByCollection($data, $fieldKey, $fieldValue, $idSelected = null)
    {
        $a = array();
        foreach ($data as $row) {
            $a[$row->$fieldKey] = $row->$fieldValue;
        }

        $output = SelectHelper::getListByArray($a, $idSelected);
        return (string) $output;
    }



    /**
     * Turn an array of object into a simple array of wanted field
     *
     * @version 25-06-2013
     * @param  array  $collection Array of objects
     * @param  string $fieldValue Name of the field
     * @return array Wanted field
     */
    public function getArrayFromCollection($collection, $fieldValue)
    {
        $a = array();
        foreach ($collection as $row) {
            $a[] = $row->$fieldValue;
        }
        return (array) $a;
    }


    /**
     * Return list of object classified in an array
     *
     * @version 25-06-2013
     * @param  array  $collection Array of objects
     * @param  string $fieldValue Name of the field
     * @return array Wanted field
     */
    public function getArrayClassified($collection, $fieldValue)
    {
        $a = array();
        foreach ($collection as $row) {
            $a[$row->$fieldValue][] = $row;
        }
        return (array) $a;
    }




    /**
     * Return list of object affected by a field in an array
     *
     * @version 25-06-2013
     * @param  array  $collection Array of objects
     * @param  string $fieldValue Name of the field
     * @return array Wanted field
     */
    public function getArrayAffected($collection, $fieldValue)
    {
        $a = array();
        foreach ($collection as $row) {
            $a[$row->$fieldValue] = $row;
        }
        return (array) $a;
    }
}