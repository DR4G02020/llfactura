<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('comprobantes', function (Blueprint $table) {

            $table->double('importeUno')->after('idFormaPago')->default(0);

            $table->integer('idFormaPago2')->after('importeUno')->default(1);
        
            $table->double('importeDos')->after('idFormaPago2')->default(0);



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('comprobantes', function (Blueprint $table) {
            $table->dropColumn('idFormaPago2');
            $table->dropColumn('importeUno');
            $table->dropColumn('importeDos');

        });
    }
};
