<?php

namespace App\Models;

use CodeIgniter\Model;

class GegenstandModel extends Model
{
    protected $table      = 'gegenstand';
    protected $primaryKey = 'gegenstand_id';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['gegenstand_id', 'bezeichnung'];

    protected $useTimestamps = true;
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}