<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attestation extends Model
{
    use HasFactory;

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
}
