<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailySpurDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('spur')->create('words', function(Blueprint $table) 
        {
            $table->increments('id');
            
            $table->string('word');
            $table->datetime('last_used')->nullable();
            $table->integer('times_used')->unsigned()->default(0);
            
            $table->timestamps();
        });
        
        $file = File::get(storage_path('words/words-common.txt'));
        
        $parts = explode(PHP_EOL,$file);
        
        foreach($parts as $p) {
            $arr = str_split($p);
            
            if(count($arr) > 3) {
                $data = [
                    'word' => trim($p),
                    'last_used' => null,
                    'times_used' => 0,
                ];

                App\DailySpur\Word::create($data);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('spur')->drop('words');
    }
}
