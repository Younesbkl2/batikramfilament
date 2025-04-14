<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtatPaiementCredit extends Model
{
    protected $table = 'etat_paiement_credit';
    public $timestamps = false;
    protected $primaryKey = 'codachat';

    protected $fillable = [
        'codachat',
        'codeclient',
        'nomclient',
        'prenomclient',
        'Numdetel',
        'NUM_TEL',
        'numappartement',
        'codeprj',
        'code_proprietaire',
        'Observations'
    ];

    // Relationships
    public function achat()
    {
        return $this->belongsTo(Achat::class, 'codachat', 'codachat');
    }

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

    public function appartement()
    {
        return $this->belongsTo(Appartement::class, 'numappartement', 'numappartement');
    }
}