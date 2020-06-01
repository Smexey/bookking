<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

/**
* ModelRazgovor – klasa za rad sa tabelom razgovor
*
* @version 1.0
 */
class ModelRazgovor extends Model
{
    protected $table      = 'razgovor';
    protected $returnType = 'App\Entities\Razgovor';
    protected $allowedFields = [
        'Korisnik1', 'Korisnik2'
    ];
}
