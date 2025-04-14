<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appartement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'appartements';
    protected $primaryKey = 'numappartement';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'numappartement',
        'blocappartement',
        'superficie',
        'etage',
        'coteappartement',
        'codeprod',
        'reservation',
        'prixdelogt',
        'codeprj',
        'code_proprietaire',
        'NumEDD',
        'Numpiece',
        'obs'
    ];

    // Relationships
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'codeprod', 'codeprod');
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'codeprj', 'codeprj');
    }

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'code_proprietaire', 'code_proprietaire');
    }

    public function achats()
    {
        return $this->hasMany(Achat::class, 'numappartement', 'numappartement');
    }

    public function lastModifiedBy()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }
    
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    

}
