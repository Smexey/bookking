<?php namespace App\Models;

use CodeIgniter\Model;

class ModelStanje extends Model {
    protected $table      = 'stanjeoglasa';
<<<<<<< HEAD
    protected $primaryKey = 'idS';
    protected $returnType = 'App\Entities\Stanje';
    protected $allowedFields = ['idS','Opis'];
=======
    protected $primaryKey = 'IdS';
    protected $returnType = 'App\Entities\Stanje';
    protected $allowedFields = ['IdS','Opis'];
>>>>>>> origin/master
}
