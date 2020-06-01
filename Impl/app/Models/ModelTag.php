<?php namespace App\Models;

use CodeIgniter\Model;

/**
* ModelTag – klasa za rad sa tabelom tag
*
* @version 1.0
 */
class ModelTag extends Model {
    protected $table      = 'tag';
    protected $primaryKey = 'IdT';
    protected $returnType = 'App\Entities\Tag';
    protected $allowedFields = ['IdT', 'Opis'];
}
