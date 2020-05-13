<?php namespace App\Models;

use CodeIgniter\Model;

class ModelStanje extends Model {
    protected $table      = 'stanjeoglasa';
    protected $primaryKey = 'idS';
    protected $returnType = 'App\Entities\Stanje';
    protected $allowedFields = ['idS','Opis'];
}
