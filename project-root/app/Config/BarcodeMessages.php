<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class BarcodeMessages extends BaseConfig
{
    private $messages;
    
    public function __construct()
    {
        $this->messages = [
            'no_schueler_barcode' => 'Es handelt sich nicht um einen Schülerausweis.',
            'no_gegenstand_barcode' => 'Es handelt sich nicht um einen Gegenstandbarcode.',
            'schueler_not_found' => 'Der Schüler ist noch nicht im System registriert.',
            'gegenstand_not_found' => 'Der Gegenstand ist noch nicht im System registriert.',
            'gegenstand_not_used' => 'Der Gegenstand ist aktuell nicht ausgeliehen.',
            'gegenstand_already_used' => 'Der Gegenstand ist bereits ausgeliehen',
            'gegenstand_already_registered' => 'Der Gegenstand ist bereits im System registriert',
            'schueler_already_registered' => 'Der Schüler ist bereits im System registriert',
        ];
    }

    public function getMessages()
    {
        return $this->messages;
    }
}