<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['hardware', 'software']);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('brand')->nullable();
            $table->string('serial_number')->unique()->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->date('entry_date');
            $table->integer('depreciation_years')->default(3);
            $table->string('image_path')->nullable();
            $table->enum('status', ['inventory', 'assigned', 'discard', 'donated', 'technical_review'])->default('inventory');
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory');
    }
};