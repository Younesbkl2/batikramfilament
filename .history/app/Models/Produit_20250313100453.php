<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';
    protected $primaryKey = 'codeprod';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Typeproduit',
        'code_proprietaire'
    ];

    // Relationships
    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'code_proprietaire', 'code_proprietaire');
    }

    public function appartements()
    {
        return $this->hasMany(Appartement::class, 'codeprod', 'codeprod');
    }

    public function parkings()
    {
        return $this->hasMany(Parking::class, 'codeprod', 'codeprod');
    }

    public function locals()
    {
        return $this->hasMany(Local::class, 'codeprod', 'codeprod');
    }
}
