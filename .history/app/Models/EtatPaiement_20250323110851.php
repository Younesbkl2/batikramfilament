<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtatPaiement extends Model
{
    protected $table = 'etat_paiement';
    public $timestamps = false;

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
        return $this->belongsTo(Proprietaire::class, 'code proprietaire', 'code proprietaire');
    }
}
