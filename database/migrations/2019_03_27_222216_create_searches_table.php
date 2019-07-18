<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searches', function (Blueprint $table) {
            $table->increments('id');
            $table->text('basic_search_text')->nullable();
            $table->text('project_name_search_text')->nullable();
            $table->text('full_text_search_text')->nullable();
            $table->text('project_description_search_text')->nullable();
            $table->text('project_owner_search_text')->nullable();
            $table->text('start_date_search_begin')->nullable();
            $table->text('start_date_search_end')->nullable();
            $table->text('end_date_search_begin')->nullable();
            $table->text('end_date_search_end')->nullable();
            $table->text('include_cancelled')->nullable();
            $table->text('include_completed')->nullable();
            $table->text('include_draft')->nullable();
            $table->text('include_pending')->nullable();
            $table->text('include_active')->nullable();
            $table->text('project_created_year_search_text')->nullable();
            $table->json('filters');
            $table->boolean('is_saved')->default(false);
            $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('searches');
    }
}
