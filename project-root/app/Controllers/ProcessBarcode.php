<?php

namespace App\Controllers;

use Config\BarcodeMessages;

use App\Libraries\GegenstandHelper;
use App\Libraries\LeihtHelper;
use App\Libraries\SchuelerHelper;

use App\Models\HatSchadenModel;

class ProcessBarcode extends BaseController
{
    private $messages;
    private $withAnimation;
    private $scanType;

    /**
     * constructor
     */
    public function __construct()
    {
        $barcodeMessages = new BarcodeMessages();

        $this->messages = $barcodeMessages->getMessages();
    } 

    /**
     * main function
     */
    public function ProcessBarcode()
    {
        header('Content-Type: application/json');

        $barcode = $this->request->getPost('barcode');
        $barcodeType = $this->request->getPost('barcodeType');
        $this->withAnimation = $this->request->getPost('animation');
        $this->scanType = $this->request->getPost('scanType');

        if($barcodeType == "schueler")
        {
            //check student
            $response = $this->checkSchuelerBarcode($barcode);
        }
        else if($barcodeType == "gegenstand")
        {
            // check item
            $response = $this->checkGegenstandBarcode($barcode);
        }
        else if($barcodeType == "")
        {
            //check both
            //first check student
            $response = $this->checkSchuelerBarcode($barcode);
            if($response['status'] == "error" && $response['error_message'] == $this->messages['no_schueler_barcode'])
            {
                //then check item, if not student barcode
                $response = $this->checkGegenstandBarcode($barcode);
            }
        }

        $response['withAnimation'] = $this->withAnimation;

        return json_encode($response);
    }

    /**
     * checks the barcode of a student
     */
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

    /**
     * checks the barcode of an item
     */
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
}