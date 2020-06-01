<?php

namespace App\Models;

use CodeIgniter\Model;

/**
* ModelPregled – klasa za rad sa tabelom pregled
*
* @version 1.0
 */
class ModelPregled extends Model
{
    protected $table      = 'pregled';
    protected $primaryKey = 'IdPr';
    protected $returnType = 'App\Entities\Pregled';
    protected $allowedFields = ['IdPr', 'BrKorisnika', 'BrOglasa', 'BrKupovina', 'BrLogovanja', 'Datum', 'NajProdavac', 'NajKupac'];

}
