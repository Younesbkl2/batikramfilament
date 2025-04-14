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
            CREATE VIEW etat_paiement_credit AS
            SELECT 
                a.codachat,
                c.codeclient,
                c.nomclient,
                c.prenomclient,
                c.Numdetel,
                c.NUM_TEL,
                a.numappartement,
                app.codeprj,
                app.code_proprietaire,
                a.Observations
            FROM
                clients c
                INNER JOIN achats a ON c.codeclient = a.codeclient
                INNER JOIN appartements app ON a.numappartement = app.numappartement
        ");
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS etat_paiement_credit');
    }
};