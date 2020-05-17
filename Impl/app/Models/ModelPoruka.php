<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class ModelPoruka extends Model
{
    protected $table      = 'poruka';
    protected $primaryKey = 'IdPo';
    protected $returnType = 'App\Entities\Poruka';
    protected $allowedFields = [
        'Korisnik1', 'Korisnik2',  'IdPo', 'Tekst'
    ];

    public function dohvatiPoruke($IdK1, $IdK2)
    {
        return $this->where("(Korisnik1 = " . $IdK1 . " and Korisnik2 = " . $IdK2 . ") or (Korisnik2 = " . $IdK1 . " and Korisnik1 = " . $IdK2 . ")", null)->orderBy("IdPo", "ASC")->findAll();
    }
}
