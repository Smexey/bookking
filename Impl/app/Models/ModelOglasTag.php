<?php namespace App\Models;

use CodeIgniter\Model;

class ModelOglasTag extends Model {
    protected $table      = 'oglastag';
    // protected $primaryKey = ['IdT', 'IdO'];
    protected $returnType = 'App\Entities\OglasTag';
    protected $allowedFields = ['IdT', 'IdO'];
}
