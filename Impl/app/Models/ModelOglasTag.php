<?php namespace App\Models;

use CodeIgniter\Model;

/**
* ModelOglasTag – klasa za rad sa tabelom oglastag
*
* @version 1.0
 */
class ModelOglasTag extends Model {
    protected $table      = 'oglastag';
    // protected $primaryKey = ['IdT', 'IdO'];
    protected $returnType = 'App\Entities\OglasTag';
    protected $allowedFields = ['IdT', 'IdO'];
}
