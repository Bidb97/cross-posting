<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBidb97CrossPostingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bidb97_cross_posting', function (Blueprint $table) {
            $table->string('model');
            $table->bigInteger('model_id');
            $table->text('posting_data');
            $table->string('resource_uri');
            $table->char('short_uri', 12);
            $table->dateTime('publish_date')->useCurrent();
            $table->boolean('is_posted')->default(false);
            $table->softDeletes();
            $table->unique(['model', 'model_id']);
            $table->unique('short_uri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bidb97_cross_posting');
    }
}
