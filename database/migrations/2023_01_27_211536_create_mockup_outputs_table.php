<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mockup_outputs', function (Blueprint $table) {
            $table->ulid();

            $table->foreignUlid('mockup_uuid')->index();
            $table->foreignUlid('team_uuid')->index();
            $table->string('file_path');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mockup_outputs');
    }
};
