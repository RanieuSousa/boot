<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Table;

class Grupouser extends Model
{
    use HasFactory;

    public $table = 'grupo_users';
}
