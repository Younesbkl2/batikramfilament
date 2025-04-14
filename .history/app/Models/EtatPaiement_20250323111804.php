<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtatPaiement extends Model
{
    protected $table = 'etat_paiement';
    public $timestamps = false;
    protected $primaryKey = 'codachat'; // Add this line

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
        // Updated to match the corrected column name
        return $this->belongsTo(Proprietaire::class, 'code_proprietaire', 'code_proprietaire');
    }
}
