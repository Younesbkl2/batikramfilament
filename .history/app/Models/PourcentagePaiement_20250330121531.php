<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PourcentagePaiement extends Model
{
    protected $table = 'pourcentage_paiement';
    public $timestamps = false;
    protected $primaryKey = 'codachat';

    protected $fillable = [
        'codachat',
        'codeclient',
        'nomclient',
        'prenomclient',
        'codebanque',
        'numappartement',
        'prixdelogt',
        'numparking',
        'prixparking',
        'Numlocal',
        'prixlocal',
        'prix_total',
        'totalite_paiements',
        'apport_personel',
        'credit_bancaire',
        'apport_personel_paye',
        'percentage_apport_personel_paye',
        'credit_bancaire_paye',
        'percentage_credit_bancaire_paye',
        'percentage_total_paye'
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

    public function banque()
    {
        return $this->belongsTo(Banque::class, 'codebanque', 'codebanque');
    }

    public function appartement()
    {
        return $this->belongsTo(Appartement::class, 'numappartement', 'numappartement');
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class, 'numparking', 'numparking');
    }

    public function local()
    {
        return $this->belongsTo(Local::class, 'Numlocal', 'Numlocal');
    }
}