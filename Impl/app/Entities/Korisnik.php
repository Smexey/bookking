<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Korisnik extends Entity
{
    public function setImejl(string $x)
    {
        $this->attributes['imejl'] = $x;
        return $this;
    }

    public function setIme($x)
    {
        $this->attributes['ime'] = $x;
    }
}
