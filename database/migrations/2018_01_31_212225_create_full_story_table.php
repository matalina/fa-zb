<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFullStoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('full_story', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('zeta_id');
            
            $table->dateTime('start_date');
            
            $table->string('title');
            $table->string('forum');
            $table->string('characters');
            $table->string('link');
            
            $table->dateTime('end_date');
            
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
        Schema::dropIfExists('full_story');
    }
}
