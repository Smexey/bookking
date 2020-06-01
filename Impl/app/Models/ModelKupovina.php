<?php

namespace App\Models;

use CodeIgniter\Model;

/**
* ModelKupovina – klasa za rad sa tabelom kupovina
*
* @version 1.0
 */
class ModelKupovina extends Model
{
    protected $table      = 'kupovina';
    protected $primaryKey = 'IdKup';
    protected $returnType = 'App\Entities\Kupovina';
    protected $allowedFields = ['IdKup', 'IdK', 'IdO', 'Datum','IdN'];

}
