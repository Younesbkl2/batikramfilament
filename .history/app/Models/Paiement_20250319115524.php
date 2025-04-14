<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

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

    public function lastModifiedBy()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }
    
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
    

}
