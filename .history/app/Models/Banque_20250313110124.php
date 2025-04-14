<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banque extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'banques';
    protected $primaryKey = 'codebanque';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nomdebanque',
        'adressedebanque',
        'numdecompte'
    ];

    // Relationships
    public function achats()
    {
        return $this->hasMany(Achat::class, 'codebanque', 'codebanque');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'codebanque', 'codebanque');
    }
}
