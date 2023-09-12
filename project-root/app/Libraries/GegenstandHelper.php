<?php

namespace App\Libraries;

use App\Models\GegenstandModel;

class GegenstandHelper
{
    public static function getAll()
    {
        $gegenstandModel = new GegenstandModel();
        $gegenstand = $gegenstandModel->FindAll();

        return $gegenstand;
    }

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

    public static function getById($id)
    {
        $gegenstandModel = new GegenstandModel();
        $gegenstand = $gegenstandModel->where("gegenstand_id", $id)->First();

        return $gegenstand;
    }

    public static function getAllBezeichnungen()
    {
        $gegenstandModel = new GegenstandModel();
        $gegenstand = $gegenstandModel->GroupBy('bezeichnung')->FindAll();

        return $gegenstand;
    }

    public static function setBezeichnung($id, $bezeichnung)
    {
        $dbData = [
            'bezeichnung' => $bezeichnung,
        ];
        
        $gegenstandModel = new GegenstandModel();

        $gegenstandModel->update($id, $dbData);
    }

    public static function deleteGegenstand($id)
    {
        $gegenstandModel = new GegenstandModel();
        $gegenstandModel->delete($id);
    }

}