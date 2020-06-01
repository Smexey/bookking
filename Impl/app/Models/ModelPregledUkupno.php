<?php

namespace App\Models;

use CodeIgniter\Model;

/**
* ModelPregledUkupno – klasa za rad sa tabelom pregledukupno
*
* @version 1.0
 */
class ModelPregledUkupno extends Model
{
    protected $table      = 'pregledukupno';
    protected $primaryKey = 'IdPu';
    protected $returnType = 'App\Entities\PregledUkupno';
    protected $allowedFields = ['IdPr', 'BrKorisnika', 'BrOglasa', 'BrKupovina', 'BrLogovanja'];

}
