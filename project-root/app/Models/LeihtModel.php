<?php

namespace App\Models;

use CodeIgniter\Model;

class LeihtModel extends Model
{
    protected $table      = 'leiht';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id', 'schueler_id', 'gegenstand_id', 'datum_start', 'datum_ende', 'datum_rueckgabe', 'weitere', 'lehrer', 'aktiv'];

    protected $useTimestamps = true;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}