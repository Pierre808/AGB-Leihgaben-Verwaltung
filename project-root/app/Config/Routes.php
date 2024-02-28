<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('index-check/(:segment)', 'Home::index/$1');

$routes->match(['get', 'post'], 'process-barcode', 'ProcessBarcode::ProcessBarcode');

$routes->match(['get', 'post'], 'gegenstandZuLeihgabeHinzufuegen', 'ProcessAjax::gegenstandZuLeihgabeHinzufuegen');
$routes->match(['get', 'post'], 'gegenstandRegistrieren', 'ProcessAjax::gegenstandRegistrieren');
$routes->match(['get', 'post'], 'gegenstandBarcodeBearbeiten', 'ProcessAjax::gegenstandBarcodeBearbeiten');
$routes->match(['get', 'post'], 'gegenstandZurueckgeben', 'ProcessAjax::gegenstandZurueckgeben');
$routes->match(['get', 'post'], 'schuelerausweisBearbeiten', 'ProcessAjax::schuelerausweisBearbeiten');

$routes->get('all-leihgabe', 'Leihgabe::alleLeihgaben');
$routes->match(['get', 'post'], 'all-leihgabe/(:any)', 'Leihgabe::alleLeihgaben/$1');
$routes->get('add-leihgabe', 'Leihgabe::leihgabeErstellen');
$routes->get('select-method', 'Leihgabe::leihgabeErstellenAuswahl');
$routes->get('add-gegenstand-to-leihgabe/(:any)', 'Leihgabe::gegenstandHinzufuegen/$1/$2');
$routes->get('show-leihgabe/(:segment)', 'Leihgabe::LeihgabeAnzeigen/$1');

$routes->get('all-gegenstande', 'Gegenstand::registrierteGegenstande');
$routes->get('add-gegenstand', 'Gegenstand::gegenstandRegistrieren');
$routes->get('add-gegenstand/(:any)', 'Gegenstand::gegenstandRegistrieren/$1/$2');
$routes->match(['get', 'post'], 'show-gegenstand/(:segment)', 'Gegenstand::gegenstandAnzeigen/$1');
$routes->get('edit-gegenstand/(:any)', 'Gegenstand::barcodeBearbeiten/$1/$2');
$routes->get('gegenstand-zurueckgeben', 'Gegenstand::gegenstandZurueckgeben');
$routes->get('gegenstand-zurueckgeben/(:any)', 'Gegenstand::gegenstandZurueckgeben/$1');
$routes->get('schaden-hinzufuegen/(:any)', 'Gegenstand::schadenHinzufuegen/$1/$2');
$routes->get('schaden-entfernen/(:any)', 'Gegenstand::schadenEntfernen/$1/$2');

$routes->get('all-schueler', 'Schueler::registrierteSchueler');
$routes->match(['get', 'post'], 'add-schueler/(:segment)', 'Schueler::schuelerHinzufuegen/$1');
$routes->match(['get', 'post'], 'add-temp-schueler', 'Schueler::tempSchuelerHinzufuegen');
$routes->match(['get', 'post'], 'add-temp-schueler/(:any)', 'Schueler::tempSchuelerHinzufuegen/$1');
$routes->match(['get', 'post'], 'show-schueler/(:segment)', 'Schueler::schuelerAnzeigen/$1');
$routes->get('edit-schueler/(:any)', 'Schueler::schuelerausweisBearbeiten/$1/$2');
$routes->get('schuelerdaten-anzeigen', 'Schueler::schuelerScannen');
$routes->get('schuelerdaten-anzeigen/(:any)', 'Schueler::schuelerScannen/$1');

