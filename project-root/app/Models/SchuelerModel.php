<?php

namespace App\Models;

use CodeIgniter\Model;

class SchuelerModel extends Model
{
    protected $table      = 'schueler';
    protected $primaryKey = 'schueler_id';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['schueler_id', 'name', 'mail'];

    protected $useTimestamps = true;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}