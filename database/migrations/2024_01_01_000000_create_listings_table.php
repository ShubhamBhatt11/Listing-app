
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up():void
    {
        Schema::create('listings',function(Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('title',120);
            $table->text('description');
            $table->string('city',60);
            $table->integer('price_cents');
            $table->string('status');
            $table->string('rejection_reason')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }
};
