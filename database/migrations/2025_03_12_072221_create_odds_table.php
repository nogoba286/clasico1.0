<?php
// database/migrations/xxxx_xx_xx_create_odds_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOddsTable extends Migration
{
    public function up()
    {
        Schema::create('odds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('market_type');
            $table->string('outcome');
            $table->decimal('odds', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('odds');
    }
}
