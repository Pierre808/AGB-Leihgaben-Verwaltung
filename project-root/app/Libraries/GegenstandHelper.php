<?php

namespace App\Libraries;

use App\Models\GegenstandModel;

class GegenstandHelper
{
    //get

    /**
     * gets all items
     */
    public static function getAll()
    {
        $gegenstandModel = new GegenstandModel();
        $gegenstand = $gegenstandModel->FindAll();

        return $gegenstand;
    }
    /**
     * gets item by id
     * 
     * @param string    $id The id of the item that should be returned
     * 
     * @return Row Item that matches the given id
     */
    public static function getById($id)
    {
        $gegenstandModel = new GegenstandModel();
        $gegenstand = $gegenstandModel->where("gegenstand_id", $id)->First();

        return $gegenstand;
    }
    /**
     * gets all the items grouped by 'bezeichnung' attribute
     * 
     * @return Results All items grouped by the 'bezeichnung' attribute
     */
    public static function getAllBezeichnungen()
    {
        $gegenstandModel = new GegenstandModel();
        $gegenstand = $gegenstandModel->GroupBy('bezeichnung')->FindAll();

        return $gegenstand;
    }


    //insert

    /**
     * Adds an item to the database
     * 
     * @param string    $id             id of new item
     * @param string    $bezeichnung    name of new item
     * 
     * @return string   inserted rows primary key
     */
    public static function add($id, $bezeichnung)
    {
        $data = [
            'gegenstand_id' => $id,
            'bezeichnung' => $bezeichnung
        ];

        $gegenstandModel = new GegenstandModel();

        // Inserts data and returns inserted row's primary key
        $gegenstandDbId = $gegenstandModel->insert($data);

        return $gegenstandDbId;
    }


    //edit

    /**
     * sets the name of an item
     * 
     * @param string    $id             id of item that should be edited
     * @param string    $bezeichnung    new name
     */
    public static function setBezeichnung($id, $bezeichnung)
    {
        $dbData = [
            'bezeichnung' => $bezeichnung,
        ];
        
        $gegenstandModel = new GegenstandModel();

        $gegenstandModel->update($id, $dbData);
    }


    //delete

    /**
     * deletes an item
     * 
     * @param string    $id     the id of the item that should be deleted
     */
    public static function deleteGegenstand($id)
    {
        $gegenstandModel = new GegenstandModel();
        $gegenstandModel->delete($id);
    }

}