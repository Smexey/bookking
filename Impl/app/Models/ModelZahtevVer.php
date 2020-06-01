<?php

namespace App\Models;

use CodeIgniter\Model;

/**
* ModelZahtevVer – klasa za rad sa tabelom zahtevver
*
* @version 1.0
 */
class ModelZahtevVer extends Model
{
    protected $table      = 'zahtevver';
    protected $primaryKey = 'IdZ';
    protected $returnType = 'App\Entities\ZahtevVer';
    protected $allowedFields = ['IdZ', 'Stanje', 'Odobrio', 'Podneo', 'Fajl'];

    /**
     * Fukncija koja vraca sve zahteve za verifikaciju korisnika cije je stanje podnet
     *
     * @return App\Entities\ZahtevVer[]
     */
    public function dohvatiSvePodneteZahteve()
    {
        $query = $this->query("SELECT IdZ, Podneo FROM zahtevver WHERE Stanje='podnet'");
        return $query->getResult();
        //return $this->where('Stanje', 'podnet')->findAll();
    }

    /**
     * Funkcija koja proverava da li postoji zahtev sa stanjem podnet za zadatog korisnika
     *
     * @param int $Podneo
     * @return bool
     */
    public function proveraZahtevPodnet($Podneo)
    {
        $podnetiZahtevi = $this->where(['Podneo' => $Podneo, 'Stanje' => 'podnet'])->findAll();
        return count($podnetiZahtevi) != 0;
    }

    /**
     * Funkcija koja vraca zahtev za verifikaciju korisnika sa zadatim id-em cije je stanje podnet
     *
     * @param int $podneo
     * @return App\Entities\ZahtevVer
     */
    public function dohvatiPodnetZahtevKorisnika($podneo)
    {
        return $this->where(['Podneo' => $podneo, 'Stanje' => 'podnet'])->first();
    }
}
