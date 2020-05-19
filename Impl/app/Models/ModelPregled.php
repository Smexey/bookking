<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPregled extends Model
{
    protected $table      = 'pregled';
    protected $primaryKey = 'IdPr';
    protected $returnType = 'App\Entities\Pregled';
    protected $allowedFields = ['IdPr', 'BrKorisnika', 'BrOglasa', 'BrKupovina', 'BrLogovanja', 'Datum', 'NajProdavac', 'NajKupac'];

}
