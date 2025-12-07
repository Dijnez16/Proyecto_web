<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventory', function (Blueprint $table) {
            // Cambiar de VARCHAR(500) a TEXT para URLs largas
            $table->text('qr_code')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('inventory', function (Blueprint $table) {
            // Revertir a VARCHAR(500)
            $table->string('qr_code', 500)->nullable()->change();
        });
    }
};