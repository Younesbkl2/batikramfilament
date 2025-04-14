<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projets';
    protected $primaryKey = 'codeprj';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Libelleprj',
        'adresseprj',
        'datedebuttrvx',
        'datefintrvx',
        'code_proprietaire'
    ];

    // Automatically cast date fields to Carbon instances
    protected $casts = [
        'datedebuttrvx' => 'datetime',
        'datefintrvx' => 'datetime',
    ];


    // Relationships
    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'code_proprietaire', 'code_proprietaire');
    }

    public function appartements()
    {
        return $this->hasMany(Appartement::class, 'codeprj', 'codeprj');
    }
}
