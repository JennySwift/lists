<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('parent_id')->unsigned()->nullable()->index();
            $table->string('title')->index();
            //I got an error when I tried using index() here
            $table->text('body')->nullable();
            $table->integer('index')->nullable();
            $table->integer('category_id')->unsigned()->nullable()->index();
            $table->decimal('priority', 10, 1)->index();
            $table->boolean('favourite')->default(false)->index();
//            $table->boolean('pinned')->default(false)->index();
            $table->integer('urgency')->nullable()->index();
            $table->dateTime('alarm')->nullable()->index();
            $table->dateTime('not_before')->nullable()->index();

            $table->enum('recurring_unit', ['minute', 'hour', 'day', 'week', 'month', 'year'])->nullable()->index();
            $table->integer('recurring_frequency')->nullable()->index();
            
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('items');
    }
}
