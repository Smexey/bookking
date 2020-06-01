<?php

namespace App\Models;

use CodeIgniter\Model;

/**
* ModelKorisnik – klasa za rad sa tabelom korisnik
*
* @version 1.0
 */
class ModelKorisnik extends Model
{
    protected $table      = 'korisnik';
    protected $primaryKey = 'IdK';
    protected $returnType = 'App\Entities\Korisnik';
    protected $allowedFields = ['IdK', 'Imejl', 'Sifra', 'Ime', 'Prezime', 'Adresa', 'Grad', 'Drzava', 'PostBroj', 'Stanje', 'IdR', 'IdMod', 'IdA'];

    /**
     * Funkcija koja vraca sve korisnike ciji je naslov ili sadrzaj jednak prosledjenom parametru
     *
     * @param String $tekst
     * @return App\Entities\Korisnik[]
     */
    public function pretraga($tekst)
    {
        return $this->like('naslov', $tekst)
            ->orLike('sadrzaj', $tekst)->findAll();
    }

    /**
     * Funkcija koja dohvata korisnika sa zadatim imejlom
     *
     * @param String $imejl
     * @return App\Entities\Korisnik
     */
    public function dohvatiImejl($imejl)
    {
        return $this->where('imejl', $imejl)->find();
    }
}
