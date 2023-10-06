<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("owner_id")->unsigned();
            $table->string("type");
            $table->string("name");
            $table->string("hash");
            $table->string("path")->nullable();
            $table->timestamps();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->index("owner_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
