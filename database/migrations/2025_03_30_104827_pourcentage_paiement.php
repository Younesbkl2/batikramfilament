<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE VIEW pourcentage_paiement AS
            SELECT 
                a.codachat,
                a.codeclient,
                c.nomclient,
                c.prenomclient,
                a.codebanque,
                a.numappartement,
                app.prixdelogt,
                a.numparking,
                pk.prixparking,
                a.Numlocal,
                lc.prixlocal,
                (COALESCE(app.prixdelogt, 0) + 
                 COALESCE(pk.prixparking, 0) + 
                 COALESCE(lc.prixlocal, 0)) AS prix_total,
                
                COALESCE(SUM(p.montantpaie), 0) AS totalite_paiements,
                
                a.apport_personel,
                a.credit_bancaire,
                
                COALESCE(SUM(CASE WHEN p.modepaie IN ('Espece', 'Versement', 'Remboursement') 
                             THEN p.montantpaie ELSE 0 END), 0) AS apport_personel_paye,
                
                ROUND(
                    (COALESCE(SUM(CASE WHEN p.modepaie IN ('Espece', 'Versement', 'Remboursement') 
                                THEN p.montantpaie ELSE 0 END), 0) /
                    NULLIF(a.apport_personel, 0)) * 100,
                    2
                ) AS percentage_apport_personel_paye,
                
                COALESCE(SUM(CASE WHEN p.modepaie = 'Crédit Bancaire' 
                             THEN p.montantpaie ELSE 0 END), 0) AS credit_bancaire_paye,
                
                ROUND(
                    (COALESCE(SUM(CASE WHEN p.modepaie = 'Crédit Bancaire' 
                                THEN p.montantpaie ELSE 0 END), 0) /
                    NULLIF(a.credit_bancaire, 0)) * 100,
                    2
                ) AS percentage_credit_bancaire_paye,
                
                ROUND(
                    (COALESCE(SUM(p.montantpaie), 0) /
                    NULLIF((COALESCE(app.prixdelogt, 0) + 
                           COALESCE(pk.prixparking, 0) + 
                           COALESCE(lc.prixlocal, 0)), 0)) * 100,  -- Fixed parentheses here
                    2
                ) AS percentage_total_paye
                
            FROM achats a
            LEFT JOIN clients c ON a.codeclient = c.codeclient
            LEFT JOIN banques b ON a.codebanque = b.codebanque
            LEFT JOIN appartements app ON a.numappartement = app.numappartement
            LEFT JOIN parkings pk ON a.numparking = pk.numparking
            LEFT JOIN locals lc ON a.Numlocal = lc.Numlocal
            LEFT JOIN paiements p ON a.codachat = p.codachat
            GROUP BY 
                a.codachat, a.codeclient, c.nomclient, c.prenomclient, a.codebanque,
                a.numappartement, app.prixdelogt, a.numparking, pk.prixparking,
                a.Numlocal, lc.prixlocal, a.apport_personel, a.credit_bancaire
        ");
    }

    public function down()
    {
        Schema::dropIfExists('pourcentage_paiement');
    }
};