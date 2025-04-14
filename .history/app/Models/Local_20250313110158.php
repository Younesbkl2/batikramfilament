<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Local extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'locals';
    protected $primaryKey = 'Numlocal';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'Numlocal',
        'surfacelocal',
        'codeprod',
        'prixlocal',
        'reservationlocal'
    ];

    // Relationships
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'codeprod', 'codeprod');
    }

    public function achats()
    {
        return $this->hasMany(Achat::class, 'Numlocal', 'Numlocal');
    }
}
