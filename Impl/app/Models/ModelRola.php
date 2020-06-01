<?php namespace App\Models;

use CodeIgniter\Model;

/**
* ModelRola – klasa za rad sa tabelom rola
*
* @version 1.0
 */
class ModelRola extends Model {
    protected $table      = 'rola';
    protected $primaryKey = 'IdR';
    protected $returnType = 'App\Entities\Rola';
    protected $allowedFields = ['IdR', 'Opis'];
}
