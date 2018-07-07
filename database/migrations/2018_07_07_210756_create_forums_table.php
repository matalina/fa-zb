<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('fa_full')->create('forums', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->string('type'); // forum, category, link
            $table->text('description');
            $table->integer('parent')->unsigned(); // 0 top level
            
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
        Schema::connection('fa_full')->dropIfExists('forums');
    }
}
