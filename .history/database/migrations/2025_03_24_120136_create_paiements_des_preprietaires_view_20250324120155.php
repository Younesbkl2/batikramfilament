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
            CREATE VIEW etat_paiement_details AS
            SELECT 
                p.codepaie,
                p.codachat,
                c.codeclient,
                c.nomclient,
                c.prenomclient,
                p.modepaie,
                p.montantpaie,
                p.datepaie,
                b.codebanque,
                prop.code_proprietaire
            FROM
                paiements p
                INNER JOIN achats a ON p.codachat = a.codachat
                INNER JOIN clients c ON p.codeclient = c.codeclient
                LEFT JOIN banques b ON a.codebanque = b.codebanque
                LEFT JOIN appartements app ON a.numappartement = app.numappartement
                LEFT JOIN proprietaires prop ON app.code_proprietaire = prop.code_proprietaire
            ORDER BY p.datepaie DESC
        ");
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS etat_paiement_details');
    }
};