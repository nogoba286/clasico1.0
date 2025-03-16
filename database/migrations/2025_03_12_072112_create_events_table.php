<?php
// database/migrations/xxxx_xx_xx_create_events_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('sport_type');
            $table->string('event_name');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['Scheduled', 'Live', 'Completed', 'Canceled'])->default('Scheduled');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}
