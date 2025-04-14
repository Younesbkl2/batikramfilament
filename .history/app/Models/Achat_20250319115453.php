<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'achats';
    protected $primaryKey = 'codachat';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'codeclient',
        'codeprod',
        'codebanque',
        'numappartement',
        'numparking',
        'Numlocal',
        'Observations',
        'ID_ATTESTATION'
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class, 'codeclient', 'codeclient');
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'codeprod', 'codeprod');
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

    public function attestation()
    {
        return $this->belongsTo(Attestation::class, 'ID_ATTESTATION', 'ID_ATTESTATION');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'codachat', 'codachat');
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