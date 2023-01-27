<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mockup_layers', function (Blueprint $table) {
            $table->ulid();
            $table->foreignUlid('mockup_uuid');

            $table->string('label');
            $table->unsignedSmallInteger('index');

            $table->unsignedSmallInteger('width');
            $table->unsignedSmallInteger('height');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mockup_layers');
    }
};
