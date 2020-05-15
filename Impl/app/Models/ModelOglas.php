<?php namespace App\Models;

use CodeIgniter\Model;

class ModelOglas extends Model {
    protected $table      = 'oglas';
    protected $primaryKey = 'IdO';
    protected $returnType = 'App\Entities\Oglas';
    protected $allowedFields = ['IdO','IdK','IdS','Autor','Naslov',
                                'Opis','Cena','Naslovnica'];
<<<<<<< HEAD
=======

    public function dohvatiSveOglaseKorisnika($IdK){
        return $this->where('IdK', $IdK)->findAll();
    }
>>>>>>> origin/master
 
}
