<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rss_feeds', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->index();
            $table->string('url', 250);
            $table->foreignId('campaign_id')->index();
            $table->foreignId('template_send_id');
            $table->integer('send_frequency');
            $table->integer('target_hour');
            $table->timestamp('last_reviewed_at')->index();
            $table->timestamp('next_review_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rss_feeds');
    }
};
