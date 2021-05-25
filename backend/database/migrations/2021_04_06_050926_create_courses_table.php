<?php

use App\Enums\CourseType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->integer('instructor_id');
            $table->integer('status')->default(CourseType::Draft)->nullable();
            $table->integer('type')->default(CourseType::NORMAL);
            $table->string('title')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->text('introduce')->nullable();
            $table->double('price')->default(0.0)->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->boolean('instructor_in')->default(false);
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
        Schema::dropIfExists('courses');
    }
}
