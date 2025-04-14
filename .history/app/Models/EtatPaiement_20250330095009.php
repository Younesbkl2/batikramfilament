<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtatPaiement extends Model
{
    protected $table = 'etat_paiement';
    public $timestamps = false;
    protected $primaryKey = 'codachat';

    // Add the new fillable fields
    protected $fillable = [
        'codachat',
        'codeclient',
        'numappartement',
        'prix_appartement',
        'numparking',
        'prix_parking',
        'Numlocal',
        'prix_local',
        'total_prix',
        'total_depense',
        'reste_a_payer',
        'pourcentage_paye',
        'statue_paiment',
        'codeprj',
        'code_proprietaire'
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class, 'codeclient', 'codeclient');
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'codeprj', 'codeprj');
    }

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'code_proprietaire', 'code_proprietaire');
    }

    // New relationship for local
    public function local()
    {
        return $this->belongsTo(Local::class, 'Numlocal', 'Numlocal');
    }
}