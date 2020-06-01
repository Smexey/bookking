<?php namespace App\Models;

use CodeIgniter\Model;

/**
* ModelPrijava – klasa za rad sa tabelom prijava
*
* @version 1.0
 */
class ModelPrijava extends Model {
    protected $table      = 'prijava';
    protected $primaryKey = ['IdPr'];
    protected $returnType = 'App\Entities\Prijava';
    protected $allowedFields = ['IdK', 'IdO', 'Opis'];

    /**
     * Funkcija koja vraca broj prijava za oglas sa zadatim id-em
     *
     * @param int $IdO
     * @return int
     */
    public function brojPrijavaZaOglas($IdO){
        return count($this->where('IdO', $IdO)->findAll());
    }
}
