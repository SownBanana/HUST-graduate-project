<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('owner_id');
//            $table->integer('message_id')->nullable();
            $table->string('assetable_type')->nullable();
            $table->integer('assetable_id')->nullable();
            $table->string('url');
            $table->string('type')->nullable();
            $table->string('size')->nullable();
            $table->boolean('is_public')->default(true);
            $table->softDeletes();
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
        Schema::dropIfExists('assets');
    }
}
