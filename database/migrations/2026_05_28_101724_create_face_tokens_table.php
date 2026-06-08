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
        Schema::create('face_tokens', function (Blueprint $table) {
            $table->id('id_face_token');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->longText('descriptor'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_tokens');
    }
};
