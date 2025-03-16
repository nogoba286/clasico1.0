<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('league_id'); // Unique league identifier
            $table->unsignedBigInteger('sport_id'); // Foreign key reference to sports table
            $table->string('name'); // League name
            $table->string('logo')->nullable(); // League logo URL
            $table->string('country'); // Country name
            $table->string('flag')->nullable(); // Country flag URL
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leagues');
    }
}
