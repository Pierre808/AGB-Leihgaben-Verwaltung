<?php

namespace App\Controllers;

use Config\BarcodeMessages;

use App\Libraries\GegenstandHelper;
use App\Libraries\LeihtHelper;
use App\Libraries\SchuelerHelper;

use App\Models\HatSchadenModel;

class ProcessAjax extends BaseController
{
    /**
     * add an item to loan (creates loan)
     */
    public function gegenstandZuLeihgabeHinzufuegen()
    {
        header('Content-Type: application/json');

        try
        {
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
        }
        catch (Exception $e)
        {
            $response['status'] = "error";
            $response['error_message'] = $e->getMessage();
        }

        return json_encode($response);
    }

    /**
     * register an item
     */
    public function gegenstandRegistrieren()
    {
        header('Content-Type: application/json');

        try
        {
            $gegenstandId = $this->request->getPost('gegenstandId');
    
            GegenstandHelper::add($gegenstandId, "/");
            
            $response['status'] = "ok";
        }
        catch (Exception $e)
        {
            $response['status'] = "error";
            $response['error_message'] = $e->getMessage();
        }

        return json_encode($response);
    }

    /**
     * edit the id of an item
     */
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

    /**
     * return an item
     */
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

    /**
     * edit the id of a student
     */
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