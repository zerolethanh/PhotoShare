<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateEventGroupByTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_group_by', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('group_by', 100)->nullable();
            $table->string('description', 100)->nullable();
            $table->nullableTimestamps();

            

            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_group_by');
    }
}
