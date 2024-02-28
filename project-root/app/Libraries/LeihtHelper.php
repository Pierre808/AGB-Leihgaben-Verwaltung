<?php

namespace App\Libraries;

use App\Models\LeihtModel;

class LeihtHelper
{
    //get
    
    /**
     * gets loan by id
     * 
     * @param string    $id     the id of the loan
     * 
     * @return row      the loan
     */
    public static function getById($id)
    {
        $leihtModel = new LeihtModel();
        $leiht = $leihtModel->where("id", $id)->First();

        return $leiht;
    }
    /**
     * gets all active loans and orders them in DESC order
     * 
     * @return row active loans in DESC order
     */
    public static function getActiveDesc()
    {
        $leihtModel = new LeihtModel();

        $leiht = $leihtModel->where("aktiv", 1)->orderBy("datum_start", "DESC")->FindAll();

        return $leiht;
    }
    /**
     * gets all the loans that are overdue
     * 
     * @return row all overdue loans
     */
    public static function getUeberfaellig()
    {
        $leihtModel = new LeihtModel();

        $leiht = $leihtModel->where("aktiv", "1")->FindAll();

        $count = count($leiht);
        for($i = 0; $i < $count; $i++)
        {
            $date = $leiht[$i]['datum_ende'];
            
            if($date == "" || date("Y-m-d H:i:s") < $date)
            {
                unset($leiht[$i]);
            }
        }

        $leiht = array_values($leiht);

        return $leiht;
    }
    /**
     * gets all loans that are overdue and where made by a given student
     * 
     * @param string    $schuelerId     the id of the student
     * 
     * @return row      all loans that match the student id and are overdue
     */
    public static function getUeberfaelligBySchuelerId($schuelerId)
    {
        $leihtModel = new LeihtModel();

        $leiht = $leihtModel->where("aktiv", "1")->where('schueler_id', $schuelerId)->FindAll();

        $count = count($leiht);
        for($i = 0; $i < $count; $i++)
        {
            $date = $leiht[$i]['datum_ende'];
            
            if($date == "" || date("Y-m-d H:i:s") < $date)
            {
                unset($leiht[$i]);
            }
        }

        $leiht = array_values($leiht);

        return $leiht;
    }
    /**
     * gets active loans by item id
     * 
     * @param string    $gegenstand_id  the id of the item
     * 
     * @return row      the first row, that contains an active loan with the given item id
     */
    public static function getActiveByGegenstandId($gegenstand_id)
    {
        $leihtModel = new LeihtModel();
        $leiht = $leihtModel->where("gegenstand_id", $gegenstand_id)->where("aktiv", 1)->First();

        return $leiht;
    }
    /**
     * gets all loans by item id
     * 
     * @param string    $gegenstand_id  the item id
     * 
     * @return row      all loans, that contain the item id
     */
    public static function getByGegenstandId($gegenstand_id)
    {
        $leihtModel = new LeihtModel();
        $leiht = $leihtModel->where("gegenstand_id", $gegenstand_id)->Find();

        return $leiht;
    }
    /**
     * gets all active loans by student id and orders them in DESC order
     * 
     * @param string    $schueler_id    the student id
     * 
     * @return row      all active loans that contain the student id, ordered in DESC order
     */
    public static function getActiveBySchuelerIdDESC($schueler_id)
    {
        $leihtModel = new LeihtModel();
        $leiht = $leihtModel->where("schueler_id", $schueler_id)->where("aktiv", 1)->OrderBy('datum_start', 'DESC')->Find();

        return $leiht;
    }
    /**
     * gets all loans by student id
     * 
     * @param string    $schueler_id    the id of the student
     * 
     * @return row      all loans made by the student with the given id
     */
    public static function getBySchuelerId($schueler_id)
    {
        $leihtModel = new LeihtModel();
        $leiht = $leihtModel->where("schueler_id", $schueler_id)->Find();

        return $leiht;
    }
    /**
     * gets all loans by student id and orders them in DESC order
     * 
     * @param string    $schueler_id    the id of the student
     * 
     * @return row      all loans made by the student with the given id, the loans are ordered in DESC order
     */
    public static function getBySchuelerIdDESC($schueler_id)
    {
        $leihtModel = new LeihtModel();
        $leiht = $leihtModel->where("schueler_id", $schueler_id)->OrderBy('datum_start', 'DESC')->Find();

        return $leiht;
    }
    /**
     * gets all the teachers
     * 
     * @return row  all loans grouped by teacher
     */
    public static function getAllLehrer()
    {
        $leihtModel = new LeihtModel();

        $leiht = $leihtModel->GroupBy('lehrer')->FindAll();

        return $leiht;
    }


    //insert

    /**
     * adds a loan to the database
     * 
     * @param string    $schueler_id    the id of the student borrowing the item
     * @param string    $gegenstand_id  the id of the item, that should be borrowed
     * @param string    $datum_start    the datetime when the loan was made
     * @param string    $datum_ende     the datetime when the loan should end
     * @param string    $weitere        other students (in case of group loan)
     * @param string    $lehrer         the teacher who runs through the loan process
     * @param int       $aktiv          0 for inactive, 1 for active loan
     * 
     * @return string   the id of the inserted row
     */
    public static function add($schueler_id, $gegenstand_id, $datum_start = false, $datum_ende = false, $weitere = false, $lehrer = false, $aktiv = 1)
    {
        $datumStart = $datum_start;
        $datumEnde = $datum_ende;

        if($datum_start == false)
        {
            $datumStart = date("Y-m-d H:i:s");
        }
        if($datum_ende == false)
        {
            $datumEnde = null;
        }

        $data = [
            'schueler_id' => $schueler_id,
            'gegenstand_id' => $gegenstand_id,
            'datum_start' => $datumStart,
            'datum_ende' => $datumEnde,
            'weitere' => $weitere,
            'lehrer' => $lehrer,
            'aktiv'    => $aktiv,
        ];

        $leihtModel = new LeihtModel();

        // Inserts data and returns inserted row's primary key
        $leihtDbId = $leihtModel->insert($data);

        return $leihtDbId;
    }

    
    //edit

    /**
     * updates the id of a student that made a loan
     * 
     * @param string    $id             the id of the loan
     * @param string    $newSchuelerId  the new id that should be assigned
     */
    public static function setSchuelerId($id, $newSchuelerId)
    {
        $dbData = [
            'schueler_id' => $newSchuelerId,
        ];
        
        $leihtModel = new LeihtModel();

        $leihtModel->update($id, $dbData);
    }
    /**
     * updates the id of an item inside a loan
     * 
     * @param string    $id                 the id of the loan
     * @param string    $newGegenstandId    the new id of the item
     */
    public static function setGegenstandId($id, $newGegenstandId)
    {
        $dbData = [
            'gegenstand_id' => $newGegenstandId,
        ];
        
        $leihtModel = new LeihtModel();

        $leihtModel->update($id, $dbData);
    }
    /**
     * return an item
     * 
     * @param string    $gegenstandId   the id of the item, that should be returned
     * 
     * @return boolean  true if item was successfully returned, false if item was not borrowed
     */
    public static function zurueckgeben($gegenstandId)
    {
        $leihtModel = new LeihtModel();

        $leiht = $leihtModel->where('gegenstand_id', $gegenstandId)->where('aktiv', 1)->Find();

        if($leiht != null)
        {
            $data = [
                'datum_rueckgabe' => date("Y-m-d H:i:s"),
                'aktiv' => 0,
            ];

            $leihtModel->update($leiht[0]['id'], $data);
            return true;
        }

        return false;
    }
}