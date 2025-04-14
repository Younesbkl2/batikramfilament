<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\ModelObserver;
use App\Models\Achat;
use App\Models\Appartement;
use App\Models\Attestation;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Local;
use App\Models\Paiement;
use App\Models\Parking;
use App\Models\Produit;
use App\Models\Projet;
use App\Models\Proprietaire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $models = [
            Achat::class, Appartement::class, Attestation::class, Banque::class,
            Client::class, Contact::class, Local::class, Paiement::class, 
            Parking::class, Produit::class, Projet::class, Proprietaire::class
        ];
    
        foreach ($models as $model) {
            $model::observe(ModelObserver::class);
        }
    }
}
