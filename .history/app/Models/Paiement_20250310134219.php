<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';
    protected $primaryKey = 'codepaie';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'modepaie',
        'codebanque',
        'montantpaie',
        'datepaie',
        'codeclient',
        'codachat'
    ];


    // Automatically cast date fields to Carbon instances
    protected $casts = [
        'datepaie' => 'datetime',
    ];

    // Relationships
    public function banque()
    {
        return $this->belongsTo(Banque::class, 'codebanque', 'codebanque');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'codeclient', 'codeclient');
    }

    public function achat()
    {
        return $this->belongsTo(Achat::class, 'codachat', 'codachat');
    }
}
