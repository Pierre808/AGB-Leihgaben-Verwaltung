<?php

namespace App\Models;

use CodeIgniter\Model;

class SchadenModel extends Model
{
    protected $table      = 'schaden';
    protected $primaryKey = 'bezeichnung';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['bezeichnung'];

    protected $useTimestamps = true;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}