<?php

namespace App\Models;

use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

/**
* ModelPoruka – klasa za rad sa tabelom poruka
*
* @version 1.0
 */
class ModelPoruka extends Model
{
    protected $table      = 'poruka';
    protected $primaryKey = 'IdPo';
    protected $returnType = 'App\Entities\Poruka';
    protected $allowedFields = [
        'Korisnik1', 'Korisnik2',  'IdPo', 'Tekst'
    ];

    /**
     * Funkcija koja vraca sve poruke izmedju dva korisnika sa zadatim id-ovima
     *
     * @param int $IdK1
     * @param int $IdK2
     * @return App\Entities\Poruka[]
     */    
    public function dohvatiPoruke($IdK1, $IdK2)
    {
        return $this->where("(Korisnik1 = " . $IdK1 . " and Korisnik2 = " . $IdK2 . ") or (Korisnik2 = " . $IdK1 . " and Korisnik1 = " . $IdK2 . ")", null)->orderBy("IdPo", "ASC")->findAll();
    }
}
