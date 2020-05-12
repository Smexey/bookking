<?php namespace App\Models;

use CodeIgniter\Model;

class ModelZahtevVer extends Model {
    protected $table      = 'zahtevver';
    protected $primaryKey = 'IdZ';
    protected $returnType = 'App\Entities\ZahtevVer';
    protected $allowedFields = ['IdZ', 'Stanje', 'Odobrio', 'Podneo', 'Fajl'];

    public function dohvatiSvePodneteZahteve(){
        $query = $this->query("SELECT IdZ, Podneo FROM zahtevver WHERE Stanje='podnet'");
        return $query->getResult();
        //return $this->where('Stanje', 'podnet')->findAll();
    }

    public function proveraZahtevPodnet($Podneo){
        $podnetiZahtevi = $this->where(['Podneo' => $Podneo, 'Stanje' => 'podnet'])->findAll();
        return count($podnetiZahtevi) != 0; ///promeniti u == 1
    }
}
