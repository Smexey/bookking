<?php namespace App\Models;

use CodeIgniter\Model;

/**
* ModelStanje – klasa za rad sa tabelom stanjeoglasa
*
* @version 1.0
 */
class ModelStanje extends Model {
    protected $table      = 'stanjeoglasa';
    protected $primaryKey = 'IdS';
    protected $returnType = 'App\Entities\Stanje';
    protected $allowedFields = ['IdS','Opis'];
}
