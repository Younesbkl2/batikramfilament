<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Champ1',
        'Champ2',
        'Champ3',
        'Champ4',
        'Champ5',
        'Champ6',
        'Champ7',
        'OBS'
    ];

    // No relationships defined
}
