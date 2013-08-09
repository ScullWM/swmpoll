<?php

namespace Project\Model;

class Trame extends CoreModel {

    /**
     * Export tache to exportable csv with correct formated data
     *
     * @version 09-08-2013
     * @param array    $taches   [description]
     * @param chantier $chantier [description]
     * @return [array]           [All content line]
     */
    public function setTachesToExport(array $taches, chantier $chantier)
    {
        $output = array();
        
        foreach ($taches as $t) {
            $output[$t->id][] = $chantier->referenceinterne;
            $output[$t->id][] = $t->designation;
            $output[$t->id][] = $t->batimentvalue;
            $output[$t->id][] = $t->niveauvalue;
            $output[$t->id][] = $t->zonevalue;
            $output[$t->id][] = $t->techniquevalue;
            $output[$t->id][] = $t->tachevalue;
            $output[$t->id][] = outils::convertMinToHour($t->nbrehmarchebase);
            $output[$t->id][] = outils::convertMinToHour($t->nbrehtravauxsuppl);
            $output[$t->id][] = $t->tauxsoustraitance;
        }

        return (array)$output;
    }

    /**
     * With filename return all content line to an array
     *
     * @version 09-08-2013
     * @param  [string] $csvFilename [URI Csv Filename]
     * @return [array]               [All content line]
     */
    public function parseCsvFile($csvFilename)
    {
        $file = new SplFileObject($csvFilename);
        $file->setFlags(SplFileObject::READ_CSV);
        
        // For each csv line, encode cell data in an array
        $csv = array();
        foreach ($file as $row) {
            $line = $file->fgets(); 
            $csv[] = explode(';', $line); 
        }

        // exclude first line
        if($csv[0][2]=="Batiment") unset($csv[0]);

        return (array)$csv;
    }

    /**
     * Turn a param cell into valid identifier to do things
     * 
     * @version 09-08-2013
     * @param  [int] $typeField  [description]
     * @param  [string] $data       [description]
     * @param  [int] $chantierid [description]
     * @return [int]             [param identifier]
     */
    public function getParamValid($typeField, $data, $chantierid)
    {
        global $app;

        // obtain int value from a string. Getting array from project's params
        $paramJdv = $app->params->jdv_link[$typeField];

        // [1]
        if(is_numeric($data)===true) return $this->setParam($chantierid, $data, $data, $paramJdv);

        // [1::Designation]
        $exp = explode('::', $data);
        if(is_numeric($exp[0])===true) return $this->setParam($chantierid, $exp[0], $exp[1], $paramJdv);

        return false;
        // [Designation] --> But how to get the key ? function magic() ?
        //if(is_string($data)===true) return $this->setParam($chantierid, $data, $data, $paramJdv);
    }

    /**
     * Send Param information, will create it if need
     *
     * @version 09-08-2013
     * @param [int]  $chantierid    [No alias param to chantier]
     * @param [int] $paramKey      [description]
     * @param [string] $paramValue    [description]
     * @param [int] $paramJdv [description]
     */
    public function setParam($chantierid, $paramKey, $paramValue, $paramJdv)
    {
        $isJdv = jdv::find('one', array('conditions'=>array('chantierid = ? AND value = ?', $chantierid, $paramKey), 'order'=>''));

        if(count($isJdv)==1) return (int)$paramKey;

        $newJdv             = new jdv();
        $newJdv->isjdv      = 0;
        $newJdv->chantierid = $chantierid;
        $newJdv->jdvid      = $paramJdv;
        $newJdv->libelle    = $paramValue;
        $newJdv->value      = $paramKey;
        $newJdv->actif      = 1;
        $newJdv->save();

        return (int)$newJdv->jdvid;
    }

    /**
     * Export Data in CSV
     *
     * @version  08-08-2013
     * @param  [array] $cols     [Name description for all calls]
     * @param  [array] $datas    [All data in a correct order]
     * @param  string $filename [filename for csv output]
     * @return CSV Output, nothing for you php
     */
    public function renderCsv(array $cols, array $datas, $filename="export")
    {
        header("content-type: application/vnd.ms-excel; charset=UTF-8");
        header('Content-Disposition: attachement; filename="'.$filename.'.csv"');     

        echo implode(';', $cols).PHP_EOL;
        foreach ($datas as $d) {
            echo implode(';', $d).PHP_EOL;
        }
    }
}