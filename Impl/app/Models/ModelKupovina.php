<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelKupovina extends Model
{
    protected $table      = 'kupovina';
    protected $primaryKey = 'IdKup';
    protected $returnType = 'App\Entities\Kupovina';
    protected $allowedFields = ['IdKup', 'IdK', 'IdO', 'Datum','IdN'];

}
