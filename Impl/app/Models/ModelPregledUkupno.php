<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPregledUkupno extends Model
{
    protected $table      = 'pregledukupno';
    protected $primaryKey = 'IdPu';
    protected $returnType = 'App\Entities\PregledUkupno';
    protected $allowedFields = ['IdPr', 'BrKorisnika', 'BrOglasa', 'BrKupovina', 'BrLogovanja'];

}
