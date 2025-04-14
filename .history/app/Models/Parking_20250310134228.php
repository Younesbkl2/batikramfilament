<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    protected $table = 'parkings';
    protected $primaryKey = 'numparking';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'numparking',
        'surfaceparking',
        'codeprod',
        'prixparking',
        'reservationparking'
    ];

    // Relationships
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'codeprod', 'codeprod');
    }

    public function achats()
    {
        return $this->hasMany(Achat::class, 'numparking', 'numparking');
    }
}
