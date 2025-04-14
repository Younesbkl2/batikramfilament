<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW etat_paiement AS
            SELECT 
                ach.codachat,
                ach.codeclient,
                a.numappartement,
                COALESCE(a.prixdelogt, 0) AS prix_appartement,
                pkr.numparking,
                COALESCE(pkr.prixparking, 0) AS prix_parking,
                l.Numlocal,
                COALESCE(l.prixlocal, 0) AS prix_local,
                (COALESCE(a.prixdelogt, 0) + 
                 COALESCE(pkr.prixparking, 0) + 
                 COALESCE(l.prixlocal, 0)) AS total_prix,
                (SELECT COALESCE(SUM(p.montantpaie), 0) 
                 FROM paiements p 
                 WHERE p.codachat = ach.codachat) AS total_depense,
                (COALESCE(a.prixdelogt, 0) + 
                 COALESCE(pkr.prixparking, 0) + 
                 COALESCE(l.prixlocal, 0) - 
                 (SELECT COALESCE(SUM(p.montantpaie), 0) 
                  FROM paiements p 
                  WHERE p.codachat = ach.codachat)) AS reste_a_payer,
                ROUND(
                    (
                        (SELECT COALESCE(SUM(p.montantpaie), 0) 
                         FROM paiements p 
                         WHERE p.codachat = ach.codachat)
                        /
                        NULLIF((COALESCE(a.prixdelogt, 0) + 
                               COALESCE(pkr.prixparking, 0) + 
                               COALESCE(l.prixlocal, 0)), 0)
                        * 100
                    ),
                    2
                ) AS pourcentage_paye,
                CASE 
                    WHEN (SELECT COALESCE(SUM(p.montantpaie), 0) 
                         FROM paiements p 
                         WHERE p.codachat = ach.codachat) >= 
                         (COALESCE(a.prixdelogt, 0) + 
                          COALESCE(pkr.prixparking, 0) + 
                          COALESCE(l.prixlocal, 0)) 
                    THEN 'Payé' 
                    ELSE 'PAS Payé' 
                END AS statue_paiment,
                a.codeprj,
                a.code_proprietaire
            FROM
                achats ach
                LEFT JOIN appartements a ON ach.numappartement = a.numappartement
                LEFT JOIN parkings pkr ON ach.numparking = pkr.numparking
                LEFT JOIN locals l ON ach.Numlocal = l.Numlocal
            GROUP BY
                ach.codachat, ach.codeclient, a.numappartement, 
                pkr.numparking, l.Numlocal, a.codeprj, 
                a.code_proprietaire, a.prixdelogt, 
                pkr.prixparking, l.prixlocal
            ORDER BY ach.codachat
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etat_paiement');
    }
};