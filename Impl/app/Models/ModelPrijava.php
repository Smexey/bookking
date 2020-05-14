<?php namespace App\Models;

use CodeIgniter\Model;

class ModelPrijava extends Model {
    protected $table      = 'prijava';
    protected $primaryKey = ['IdPr'];
    protected $returnType = 'App\Entities\Prijava';
    protected $allowedFields = ['IdK', 'IdO'];

    public function brojPrijavaZaOglas ($IdO){
        return count($this->where('IdO', $IdO)->findAll());
    }
}
