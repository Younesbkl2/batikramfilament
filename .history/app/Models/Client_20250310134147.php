<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    protected $primaryKey = 'codeclient';
    public $incrementing = false; // Since codeclient is Short Text (not auto-incrementing)
    protected $keyType = 'string'; // Because codeclient is a string

    protected $fillable = [
        'codeclient',
        'nomclient',
        'prenomclient',
        'adresseclient',
        'Numdetel',
        'NUM_TEL',
        'email',
        'photo',
        'dossier',
        'date_de_naissance',
        'Vsp_publié'
    ];

    // Automatically cast date fields to Carbon instances
    protected $casts = [
        'date_de_naissance' => 'datetime',
    ];


    // Relationships

    public function achats()
    {
        return $this->hasMany(Achat::class, 'codeclient', 'codeclient');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'codeclient', 'codeclient');
    }
}
