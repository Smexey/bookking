<?php namespace App\Models;

use CodeIgniter\Model;

class ModelTag extends Model {
    protected $table      = 'tag';
    protected $primaryKey = 'IdT';
    protected $returnType = 'App\Entities\Tag';
    protected $allowedFields = ['IdT', 'Opis'];
}
