<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaiementsDesProprietaires extends Model
{
    protected $table = 'paiements_des_proprietaires';
    public $timestamps = false;
    protected $primaryKey = 'codepaie';

    protected $fillable = [
        'codepaie',
        'codachat',
        'codeclient',
        'nomclient',
        'prenomclient',
        'modepaie',
        'montantpaie',
        'datepaie',
        'codebanque',
        'code_proprietaire'
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class, 'codepaie', 'codepaie');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'codeclient', 'codeclient');
    }

    public function banque()
    {
        return $this->belongsTo(Banque::class, 'codebanque', 'codebanque');
    }

    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'code_proprietaire', 'code_proprietaire');
    }
}