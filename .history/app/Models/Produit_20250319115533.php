<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produits';
    protected $primaryKey = 'codeprod';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'Typeproduit',
        'code_proprietaire'
    ];

    // Relationships
    public function proprietaire()
    {
        return $this->belongsTo(Proprietaire::class, 'code_proprietaire', 'code_proprietaire');
    }

    public function appartements()
    {
        return $this->hasMany(Appartement::class, 'codeprod', 'codeprod');
    }

    public function parkings()
    {
        return $this->hasMany(Parking::class, 'codeprod', 'codeprod');
    }

    public function locals()
    {
        return $this->hasMany(Local::class, 'codeprod', 'codeprod');
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
