<?php

namespace App\Controllers;

use App\Libraries\SchuelerHelper;
use App\Libraries\GegenstandHelper;
use App\Libraries\LeihtHelper;

class Home extends BaseController
{
    public function __construct()
    {
        //constructor
    }

    public function index()
    {
        $data['page_title'] = "Home";
        $data['menuName'] = "home";
        
        $leihgaben = LeihtHelper::getUeberfaellig();
        
        for($i = 0; $i < count($leihgaben); $i++)
        {
            $leihgaben[$i]['schueler_name'] = SchuelerHelper::getById($leihgaben[$i]['schueler_id'])['name'];
            $leihgaben[$i]['gegenstand_bezeichnung'] = GegenstandHelper::getById($leihgaben[$i]['gegenstand_id'])['bezeichnung'];

            $leihgaben[$i]['formated_datum_ende'] = date_format(date_create_from_format("Y-m-d H:i:s", $leihgaben[$i]['datum_ende']), "H:i \U\h" . '\r, \a\m ' . "d.m.Y");
        }

        $data['leihgaben'] = $leihgaben;

        return view('Home/index', $data);
    }
}
