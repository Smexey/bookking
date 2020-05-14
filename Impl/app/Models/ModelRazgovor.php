<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class ModelRazgovor extends Model
{
    protected $table      = 'razgovor';
    protected $returnType = 'App\Entities\Razgovor';
    protected $allowedFields = [
        'Korisnik1', 'Korisnik2'
    ];
}
