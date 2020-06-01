<?php namespace App\Models;

use CodeIgniter\Model;

/**
* ModelOglas – klasa za rad sa tabelom oglas
*
* @version 1.0
 */
class ModelOglas extends Model {
    protected $table      = 'oglas';
    protected $primaryKey = 'IdO';
    protected $returnType = 'App\Entities\Oglas';
    protected $allowedFields = ['IdO','IdK','IdS','Autor','Naslov',
                                'Opis','Cena','Naslovnica'];

    /**
     * Funkcija koja vraca sve oglase korisnika sa zadatim id-em
     *
     * @param int $IdK
     * @return App\Entities\Oglas[]
     */          
    public function dohvatiSveOglaseKorisnika($IdK){
        return $this->where('IdK', $IdK)->findAll();
    }
 
}
