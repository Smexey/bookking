<?php namespace App\Models;

use CodeIgniter\Model;

class ModelRola extends Model {
    protected $table      = 'rola';
    protected $primaryKey = 'IdR';
    protected $returnType = 'App\Entities\Rola';
    protected $allowedFields = ['IdR', 'Opis'];
}
