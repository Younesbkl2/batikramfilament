<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proprietaire extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proprietaires';
    protected $primaryKey = 'code_proprietaire';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nom_proprietaire',
        'prenom_proprietaire'
    ];

    // Relationships
    public function produits()
    {
        return $this->hasMany(Produit::class, 'code_proprietaire', 'code_proprietaire');
    }

    public function projets()
    {
        return $this->hasMany(Projet::class, 'code_proprietaire', 'code_proprietaire');
    }

    public function appartements()
    {
        return $this->hasMany(Appartement::class, 'code_proprietaire', 'code_proprietaire');
    }
}
