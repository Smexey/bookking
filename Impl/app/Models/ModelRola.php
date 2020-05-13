<?php namespace App\Models;

use CodeIgniter\Model;

class ModelRola extends Model {
    protected $table      = 'rola';
    protected $primaryKey = 'idK';
    protected $returnType = 'App\Entities\Rola';
    protected $allowedFields = ['IdR', 'Opis'];
}
