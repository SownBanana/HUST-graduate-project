<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->string("receive_email")->nullable()->default(true);
            $table->boolean("receive_flower_new_course")->nullable()->default(false);
            $table->boolean("receive_notification")->nullable()->default(false);
            $table->boolean("receive_course_change")->nullable()->default(false);
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
        Schema::dropIfExists('students');
    }
}
