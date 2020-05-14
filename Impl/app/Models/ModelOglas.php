<?php namespace App\Models;

use CodeIgniter\Model;

class ModelOglas extends Model {
    protected $table      = 'oglas';
    protected $primaryKey = 'IdO';
    protected $returnType = 'App\Entities\Oglas';
    protected $allowedFields = ['IdO','IdK','IdS','Autor','Naslov',
                                'Opis','Cena','Naslovnica'];


    public function svi(){
        $query = $this->query("SELECT * FROM oglas WHERE EXISTS(SELECT * FROM prijava WHERE prijava.IdO=oglas.IdO)");
        return $query->get();
    }
 
}
