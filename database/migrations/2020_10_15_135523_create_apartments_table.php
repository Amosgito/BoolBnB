<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();

            $table -> text('description');
            $table -> string('title');
            $table -> text('address');
            $table -> integer('room_qt');
            $table -> integer('bathroom_qt');
            $table -> integer('bed_qt');
            $table -> integer('mq');
            $table -> boolean('visible') -> default(1);
            $table -> decimal('latitude', 8, 6) -> nullable();
            $table -> decimal('longitude', 9, 6) -> nullable();
            $table -> bigInteger('user_id') -> unsigned();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartments');
    }
}
