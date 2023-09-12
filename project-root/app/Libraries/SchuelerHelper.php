<?php

namespace App\Libraries;

use App\Models\SchuelerModel;

class SchuelerHelper
{
    //get

    /**
     * gets all students
     * 
     * @return row  all students
     */
    public static function getAll()
    {
        $schuelerModel = new SchuelerModel();
        $schueler = $schuelerModel->FindAll();

        return $schueler;
    }
    /**
     * gets a student by id
     * 
     * @param string    $id     the id of the student that should be returned
     * 
     * @return row      the student matching the id
     */
    public static function getById($id)
    {
        $schuelerModel = new SchuelerModel();
        $schueler = $schuelerModel->where("schueler_id", $id)->First();

        return $schueler;
    }
    /**
     * gets all students grouped by name
     * 
     * @return row  all students grouped by name
     */
    public static function getAllNamesGrouped()
    {
        $schuelerModel = new SchuelerModel();
        $schueler = $schuelerModel->groupBy('name')->FindAll();

        return $schueler;
    }
    /**
     * gets all students ordered by name
     * 
     * @return row all students ordered by their name
     */
    public static function getAllOrderByName()
    {
        $schuelerModel = new SchuelerModel();
        $schueler = $schuelerModel->OrderBy('name')->FindAll();

        return $schueler;
    }
    

    //insert

    /**
     * adds a student to the database
     * 
     * @param string    $id     the id of the student
     * @param string    $name   the students name
     * @param string    $mail   the mail of the student
     */
    public static function add($id, $name, $mail = null)
    {
        $data = [
            'schueler_id' => $id,
            'name' => $name,
            'mail'    => $mail,
        ];

        $schuelerModel = new SchuelerModel();

        // Inserts data and returns inserted row's primary key
        $schuelerDbId = $schuelerModel->insert($data);

        return $schuelerDbId;
    }


    //edit

    /**
     * sets the name of a student
     * 
     * @param string    $id     the id of the student
     * @param string    $name   the name that should be set
     */
    public static function setName($id, $name)
    {
        $dbData = [
            'name' => $name,
        ];
        
        $schuelerModel = new SchuelerModel();

        $schuelerModel->update($id, $dbData);
    }
    /**
     * updates the id of a student
     * 
     * @param string    $id     the students id
     * @param string    $newId  the new id that should be set
     */
    public static function setId($id, $newId)
    {
        $dbData = [
            'schueler_id' => $newId,
        ];
        
        $schuelerModel = new SchuelerModel();

        $schuelerModel->update($id, $dbData);
    }

    
    //delete

    /**
     * deletes a student
     * 
     * @param string    $id     the students id
     */
    public static function deleteSchueler($id)
    {
        $schuelerModel = new SchuelerModel();
        $schuelerModel->delete($id);
    }
}