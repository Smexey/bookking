<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelNacinKupovine extends Model
{
    protected $table      = 'nacinkupovine';
    protected $primaryKey = 'IdN';
    protected $returnType = 'App\Entities\NacinKupovine';
    protected $allowedFields = ['IdN', 'Opis'];

}
