<?php
// database/migrations/xxxx_xx_xx_create_bets_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetsTable extends Migration
{
    public function up()
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('odds_id')->constrained()->onDelete('cascade');
            $table->decimal('bet_amount', 10, 2);
            $table->decimal('potential_payout', 10, 2);
            $table->enum('status', ['Placed', 'Won', 'Lost', 'Canceled'])->default('Placed');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bets');
    }
}
