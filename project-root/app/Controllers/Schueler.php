<?php

namespace App\Controllers;

use App\Models\SchuelerModel;

use App\Libraries\SchuelerHelper;
use App\Libraries\LeihtHelper;
use App\Libraries\GegenstandHelper;

class Schueler extends BaseController
{
    public function __construct()
    {
        //$this->db = \Config\Database::connect();
        helper(['url', 'form']);
    }

    public function registrierteSchueler()
    {
        $data['page_title'] = "Registrierte Schueler";
        $data['menuName'] = "schueler";
        
        $schueler = SchuelerHelper::getAllOrderByName();

        $data['schueler'] = $schueler;

        return view('Schueler/registrierteSchueler', $data);
    }

    public function schuelerAnzeigen($schuelerId)
    {
        if($schuelerId == false)
        {
            return view('errors/html/error_404');
        }
        
        $schueler = SchuelerHelper::getById($schuelerId);
        if($schueler == null)
        {
            return view('errors/html/error_404');
        }

        if($this->request->getMethod() == "post")
        {
            $newName = $this->request->getPost('name');

            SchuelerHelper::setName($schuelerId, $newName);

            //prevent warning on reload caused by post request
            return redirect()->to("show-schueler/" . $schuelerId);
        }


        $data['page_title'] = "Schueler anzeigen";
        $data['menuName'] = "schueler";

        $data['schueler'] = $schueler;

        $data['isTemp'] = false;
        if(str_ends_with($schueler['schueler_id'], 'TEMP'))
        {
            $data['isTemp'] = true;
        }

        /*$ueberfaellig = LeihtHelper::getUeberfaelligBySchuelerId($schuelerId);
        for($i = 0; $i < count($ueberfaellig); $i++)
        {
            $gegenstand = GegenstandHelper::getById($ueberfaellig[$i]['gegenstand_id']);
            $ueberfaellig[$i]['gegenstand_bezeichnung'] = $gegenstand['bezeichnung'];  
            $ueberfaellig[$i]['formated_datum_ende'] = date_format(date_create_from_format("Y-m-d H:i:s", $ueberfaellig[$i]['datum_ende']), "H:i \U\h" . '\r, \a\m ' . "d.m.Y");
        }
        $data['ueberfaellig'] = $ueberfaellig;*/

        $aktiv = LeihtHelper::getActiveBySchuelerIdDESC($schuelerId);
        for($i = 0; $i < count($aktiv); $i++)
        {
            $gegenstand = GegenstandHelper::getById($aktiv[$i]['gegenstand_id']);
            $aktiv[$i]['gegenstand_bezeichnung'] = $gegenstand['bezeichnung'];
        }
        $data['aktiv'] = $aktiv;

        $verlauf = LeihtHelper::getBySchuelerIdDESC($schuelerId);
        for($i = 0; $i < count($verlauf); $i++)
        {
            $gegenstand = GegenstandHelper::getById($verlauf[$i]['gegenstand_id']);
            $verlauf[$i]['gegenstand_bezeichnung'] = $gegenstand['bezeichnung'];
        }
        $data['verlauf'] = $verlauf;

        return view('Schueler/schuelerAnzeigen', $data);
    }

    public function schuelerScannen($schuelerId = false)
    {
        $data['page_title'] = "Schuelerdaten anzeigen";
        $data['menuName'] = "schueler";

        $data['schuelerId'] = $schuelerId;

        $error = "";

        if($schuelerId != false)
        {
            $schueler = SchuelerHelper::getById($schuelerId);

            if(!str_starts_with($schuelerId, getenv('SCHUELER_PREFIX')))
            {
                $error = "Der Barcode entspricht nicht den Bedingungen eines Schülerausweises";
            }
            else if($schueler == null)
            {
                $error = "Es ist kein Schueler mit diesem Schuelerausweis registriert";
            }

        }

        $data['error'] = $error;

        return view('Schueler/schuelerScannen', $data);
    }

    public function schuelerHinzufuegen($schuelerId = false)
    {
        if($schuelerId == false)
        {
            return view('errors/html/error_404');
        }
        


        $data['page_title'] = "Schüler hinzufügen";
        $data['menuName'] = "add";
        $data['menuTextName'] = "ausleihe";
        
        $data['schuelerId'] = $schuelerId;

        $data['errors'] = null;
        

        if($this->request->getMethod() == "post")
        {
            $validated = $this->validate([
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Bitte geben Sie einen Namen an'
                    ]
                ],
                'mail' => [
                    'rules' => 'valid_email',
                    'errors' => [
                        'valid_email' => 'Bitte geben Sie eine gültige Email Adresse an'
                    ]
                ],
            ]);

            if(!$validated)
            {
                $data['errors'] = $this->validator->getErrors();

                
                if($this->request->getPost('mail') == "")
                {
                    unset($data['errors']['mail']);
                }

                if(count($data['errors']) != 0)
                {
                    return view('Schueler/schuelerHinzufuegen', $data);
                }
            }

            //add schueler to db and redirect if validation has no errors
            echo('success');

            $name = $this->request->getPost('name');
            $mail = $this->request->getPost('mail');

            if($mail == "")
            {
                $mail = null;
            }

            SchuelerHelper::add($schuelerId, $name, $mail);
            return redirect()->to('add-gegenstand-to-leihgabe/' . $schuelerId);

        }


        return view('Schueler/schuelerHinzufuegen', $data);
    }

    public function tempSchuelerHinzufuegen($schuelerId = false)
    {
        $data['page_title'] = "Temp. Schüler hinzufügen";
        $data['menuName'] = "add";
        $data['menuTextName'] = "ausleihe";

        $data['errors'] = null;
        
        $tempId = uniqid(getenv('SCHUELER_PREFIX'));
        $tempId .= 'TEMP';
        $data['tempId'] = $tempId;

        if($this->request->getMethod() == "post")
        {
            $validated = $this->validate([
                'name' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Bitte geben Sie einen Namen an'
                    ]
                ],
                'mail' => [
                    'rules' => 'valid_email',
                    'errors' => [
                        'valid_email' => 'Bitte geben Sie eine gültige Email Adresse an'
                    ]
                ],
            ]);

            if(!$validated)
            {
                $data['errors'] = $this->validator->getErrors();

                
                if($this->request->getPost('mail') == "")
                {
                    unset($data['errors']['mail']);
                }

                if(count($data['errors']) != 0)
                {
                    return view('Schueler/tempSchuelerHinzufuegen', $data);
                }
            }

            //add schueler to db and redirect if validation has no errors
            echo('success');

            $name = $this->request->getPost('name');
            $mail = $this->request->getPost('mail');

            if($mail == "")
            {
                $mail = null;
            }

            SchuelerHelper::add($schuelerId, $name, $mail);
            return redirect()->to('add-gegenstand-to-leihgabe/' . $schuelerId);
        }
        
        return view('Schueler/tempSchuelerHinzufuegen', $data);
    }

    public function schuelerausweisBearbeiten($schuelerId = false)
    {
        if($schuelerId == false)
        {
            return view('errors/html/error_404');
        }

        $data['page_title'] = "Schuelerausweis neu zuweisen";
        $data['menuName'] = "schueler";
        
        $data['schuelerId'] = $schuelerId;

        return view('Schueler/schuelerausweisBearbeiten', $data);
    }
}
