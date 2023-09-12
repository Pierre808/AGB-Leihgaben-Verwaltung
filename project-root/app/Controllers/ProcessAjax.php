<?php

namespace App\Controllers;

use Config\BarcodeMessages;

use App\Libraries\GegenstandHelper;
use App\Libraries\LeihtHelper;
use App\Libraries\SchuelerHelper;

use App\Models\HatSchadenModel;

class ProcessAjax extends BaseController
{
    private $messages;
    private $withAnimation;
    private $scanType;

    public function __construct()
    {
        //$_POST['barcode'] = 'AGs';
        //$_POST['barcodeType'] = '';
        //$_POST['animation'] = 'false';
        

        $barcodeMessages = new BarcodeMessages();

        $this->messages = $barcodeMessages->getMessages();
    } 

    public function ProcessBarcode()
    {
        //header('Content-Type: application/json');

        $barcode = $this->request->getPost('barcode');
        $barcodeType = $this->request->getPost('barcodeType');
        $this->withAnimation = $this->request->getPost('animation');
        $this->scanType = $this->request->getPost('scanType');

        if($barcodeType == "schueler")
        {
            //check schueler
            $response = $this->checkSchuelerBarcode($barcode);
        }
        else if($barcodeType == "gegenstand")
        {
            // check gegenstand
            $response = $this->checkGegenstandBarcode($barcode);
        }
        else if($barcodeType == "")
        {
            //check both
            //first check schueler
            $response = $this->checkSchuelerBarcode($barcode);
            if($response['status'] == "error" && $response['error_message'] == $this->messages['no_schueler_barcode'])
            {
                //then check gegenstand, if not schueler barcode
                $response = $this->checkGegenstandBarcode($barcode);
            }
        }

        $response['withAnimation'] = $this->withAnimation;

        return json_encode($response);
    }

    private function checkSchuelerBarcode($barcode) 
    {
        $response = [];

        if(!str_starts_with(strtolower($barcode), strtolower(getenv('SCHUELER_PREFIX'))))
        {
            $response['status'] = "error";
            $response['error_message'] = $this->messages['no_schueler_barcode'];
            return $response;
        }

        if($this->scanType == "edit")
        {
            $schueler = SchuelerHelper::getById($barcode);
            if($schueler == null)
            {
                $response['status'] = "ok";
                $response['redirect'] = 'show-schueler/' . $barcode;
            }
            else
            {
                $response['status'] = "error";
                $response['error_message'] = $this->messages['schueler_already_registered'];
                $response['links'] = [
                    ['link' => 'show-schueler/' . $barcode, 'link_text' => 'Schüler anzeigen'],
                ];
            }
        }
        else
        {
            $schueler = SchuelerHelper::getById($barcode);
            if($schueler == null)
            {
                $response['status'] = "error";
                $response['error_message'] = $this->messages['schueler_not_found'];
                if($this->withAnimation == "true")
                {
                    $response['links'] = [
                        ['link' => 'add-schueler/' . $barcode, 'link_text' => 'Schüler hinzufügen'],
                    ];
                }
                else
                {
                    $response['redirect'] = 'add-schueler/' . $barcode;
                }
            }
            else
            {
                $response['status'] = "ok";

                if($this->scanType == "show")
                {
                    $response['redirect'] = 'show-schueler/' . $barcode;
                }
                else
                {
                    $response['redirect'] = 'add-gegenstand-to-leihgabe/' . $barcode;
                }
            }
        }

        return $response;
    }

    private function checkGegenstandBarcode($barcode) 
    {
        $response = [];

        if(!str_starts_with(strtolower($barcode), strtolower(getenv('GEGENSTAND_PREFIX'))))
        {
            $response['status'] = "error";
            $response['error_message'] = $this->messages['no_gegenstand_barcode'];
            return $response;
        }

        if($this->scanType == "home")
        {
            //if a gegenstand is given back (isReturn)
            $leihgabe = LeihtHelper::getActiveByGegenstandId($barcode);

            if($leihgabe != null)
            {
                $response['status'] = "ok";
                $response['redirect'] = "gegenstand-zurueckgeben/" . $barcode;
            }
            else
            {
                $response['status'] = "error";
                $response['error_message'] = $this->messages['gegenstand_not_used'];
            }
        }
        else if($this->scanType == "return")
        {
            $gegenstand = GegenstandHelper::getById($barcode);

            if($gegenstand == null)
            {
                $response['status'] = "error";
                $response['error_message'] = $this->messages['gegenstand_not_found'];
                $response['links'] = [
                    ['link' => 'add-gegenstand/' . $barcode, 'link_text' => 'Gegenstand registrieren'],
                ];
            }
            else
            {
                $aktiveLeihgabe = LeihtHelper::getActiveByGegenstandId($barcode);

                if($aktiveLeihgabe == null)
                {
                    $response['status'] = "error";
                    $response['error_message'] = $this->messages['gegenstand_not_used'];
                }
                else
                {
                    $response['status'] = "ok";
                    $response['redirect'] = "gegenstand-zurueckgeben";
                }
            }
        }
        else if($this->scanType == "lend")
        {
            $gegenstand = GegenstandHelper::getById($barcode);

            if($gegenstand != null)
            {
                $leihgabe = LeihtHelper::getActiveByGegenstandId($barcode);

                if($leihgabe != null)
                {
                    //already in db        
                    $response['status'] = "error";
                    $response['error_message'] = $this->messages['gegenstand_already_used'];
                }
                else
                {
                    //ok
                    $response['status'] = "ok";
                    $response['redirect'] = "current";
                }
            }
            else
            {                
                //not found
                $response['status'] = "error";
                $response['error_message'] = $this->messages['gegenstand_not_found'];
                $response['links'] = [
                    ['link' => 'add-gegenstand/' . $barcode, 'link_text' => 'Gegenstand registrieren'],
                ];
            }
        }
        else if($this->scanType = "register")
        {
            $gegenstand = GegenstandHelper::getById($barcode);

            if($gegenstand == null)
            {
                $response['status'] = "ok";
                $response['redirect'] = "show-gegenstand/" . $barcode;

                if(session()->getFlashdata('gegenstand_redirect') != null)
                {
                    $response['redirect'] = session()->getFlashdata('gegenstand_redirect');
                    unset($_SESSION['gegenstand_redirect']);
                }
            }
            else
            {
                $response['status'] = "error";
                $response['error_message'] = $this->messages['gegenstand_already_registered'];
                $response['links'] = [
                    ['link' => 'show-gegenstand/' . $barcode, 'link_text' => 'Gegenstand anzeigen'],
                ];
            }
        }
        else if($this->scanType = "edit")
        {
            $gegenstand = GegenstandHelper::getById($barcode);

            if($gegenstand != null)
            {
                $response['status'] = "error";
                $response['error_message'] = $this->messages['gegenstand_already_registered'];
            }
            else
            {
                $response['status'] = "ok";
                $response['redirect'] = "show-gegenstand/" . $barcode;
            }
        }
        

        return $response;
    }





    public function gegenstandZuLeihgabeHinzufuegen()
    {
        header('Content-Type: application/json');

        $schuelerId = $this->request->getPost('schuelerId');
        $gegenstandId = $this->request->getPost('gegenstandId');
        $weitere = $this->request->getPost('weitere');
        $lehrer = $this->request->getPost('lehrer');
        $datumEnde = $this->request->getPost('datumEnde');

        if($weitere == "")
        {
            $weitere = 0;
        }

        if($lehrer == "")
        {
            $lehrer = "/";
        }

        $d = false;
        if($datumEnde != null)
        {
            $d = date("Y-m-d H:i:s", strtotime('+23 hours +59 minutes', strtotime($datumEnde)));
        }

        $rowId = LeihtHelper::add($schuelerId, $gegenstandId, date("Y-m-d H:i:s"), $d, $weitere, $lehrer);

        $gegenstand = GegenstandHelper::getById($gegenstandId);
        session()->setFlashdata('last-gegenstand', $gegenstand);

        $response['status'] = "ok";
        return json_encode($response);
    }

    public function gegenstandRegistrieren()
    {
        header('Content-Type: application/json');

        $gegenstandId = $this->request->getPost('gegenstandId');

        GegenstandHelper::add($gegenstandId, "/");
        
        $response['status'] = "ok";
        return json_encode($response);
    }

    public function gegenstandBarcodeBearbeiten()
    {
        header('Content-Type: application/json');

        try 
        {
            $gegenstandId = $this->request->getPost('gegenstandId');
            $newId = $this->request->getPost('newId');
    
            $gegenstandOld = GegenstandHelper::getById($gegenstandId);
            GegenstandHelper::add($newId, $gegenstandOld['bezeichnung']);
            
            $leiht = LeihtHelper::getBygegenstandId($gegenstandId);
    
            for($i = 0; $i < count($leiht); $i++)
            {
                LeihtHelper::setGegenstandId($leiht[$i]['id'], $newId);
            }
    
            $schaedenModel = new HatSchadenModel();
            $schaeden = $schaedenModel->where('gegenstand_id', $gegenstandId)->FindAll();
    
            for($i = 0; $i < count($schaeden); $i++)
            {
                $dbData = [
                    'gegenstand_id' => $newId,
                ];
                
                $schaedenModel->update($schaeden[$i]['id'], $dbData);
            }
    
            GegenstandHelper::deleteGegenstand($gegenstandId);

            
            $response['status'] = "ok";
        }
        catch (Exception $e)
        {
            $response['status'] = "error";
            $response['error_message'] = $e->getMessage();
        }
        
        return json_encode($response);
    }

    public function gegenstandZurueckgeben()
    {
        header('Content-Type: application/json');

        try 
        {
            $gegenstandId = $this->request->getPost('gegenstandId');
            
            $gegenstand = GegenstandHelper::getById($gegenstandId);
            $aktiveLeihgabe = LeihtHelper::getActiveByGegenstandId($gegenstandId);
            $leihgabe = LeihtHelper::zurueckgeben($gegenstandId);
            $schueler = SchuelerHelper::getById($aktiveLeihgabe['schueler_id']);
            session()->setFlashData('filter-post-schueler', $schueler['name']);
            $infos = [
                "schueler" => $schueler['name'],
                "gegenstand" => $gegenstand['bezeichnung'],
            ];
            session()->setFlashdata('last-zurueckgegeben', $infos);
            
            $response['status'] = "ok";
        }
        catch (Exception $e)
        {
            $response['status'] = "error";
            $response['error_message'] = $e->getMessage();
        }
        
        return json_encode($response);
    }

    public function schuelerausweisBearbeiten()
    {
        header('Content-Type: application/json');

        try 
        {
            $schuelerId = $this->request->getPost('schuelerId');
            $newId = $this->request->getPost('newId');
            
            $schuelerOld = SchuelerHelper::getById($schuelerId);
            SchuelerHelper::add($newId, $schuelerOld['name'], $schuelerOld['mail']);
            
            $leiht = LeihtHelper::getBySchuelerId($schuelerId);

            for($i = 0; $i < count($leiht); $i++)
            {
                LeihtHelper::setSchuelerId($leiht[$i]['id'], $newId);
            }

            SchuelerHelper::deleteSchueler($schuelerId);
            
            $response['status'] = "ok";
        }
        catch (Exception $e)
        {
            $response['status'] = "error";
            $response['error_message'] = $e->getMessage();
        }
        
        return json_encode($response);
    }
}