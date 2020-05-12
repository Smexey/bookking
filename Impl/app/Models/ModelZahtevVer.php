<?php namespace App\Models;

use CodeIgniter\Model;

class ModelZahtevVer extends Model {
    protected $table      = 'zahtevver';
    protected $primaryKey = 'idZ';
    protected $returnType = 'object';
    protected $allowedFields = ['IdZ', 'Stanje', 'Odobrio', 'Podneo'];

    public function dohvatiSvePodneteZahteve(){
        $query = $this->query("SELECT IdZ, Podneo FROM zahtevver WHERE Stanje='podnet'");
        return $query->getResult();
        //return $this->where('Stanje', 'podnet')->findAll();
    }
}
