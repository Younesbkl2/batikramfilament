<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attestation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'attestations';
    protected $primaryKey = 'ID_ATTESTATION';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'reservation',
        'reservation_notaire',
        'prestation',
        'remise_des_clés',
        'Num_attestation',
        'codachat',
        'date_attestation',
        'OBS'
    ];

    // Automatically cast date fields to Carbon instances
    protected $casts = [
        'date_attestation' => 'datetime',
    ];

    // Relationships
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
