<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('team_credits', function (Blueprint $table) {
            $table->ulid();

            $table->foreignUlid('team_uuid')->index();
            $table->unsignedTinyInteger('type');
            $table->unsignedSmallInteger('limit');
            $table->timestamp('expires_at')->nullable();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_credits');
    }
};
