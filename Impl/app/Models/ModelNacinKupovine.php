<?php

namespace App\Models;

use CodeIgniter\Model;

/**
* ModelNacinKupovine – klasa za rad sa tabelom nacinkupovine
*
* @version 1.0
 */
class ModelNacinKupovine extends Model
{
    protected $table      = 'nacinkupovine';
    protected $primaryKey = 'IdN';
    protected $returnType = 'App\Entities\NacinKupovine';
    protected $allowedFields = ['IdN', 'Opis'];

}
