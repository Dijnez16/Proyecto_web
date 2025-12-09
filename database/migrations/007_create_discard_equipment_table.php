<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discard_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->text('technical_opinion');
            $table->foreignId('discarded_by')->constrained('users');
            $table->date('discard_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discard_equipment');
    }
};