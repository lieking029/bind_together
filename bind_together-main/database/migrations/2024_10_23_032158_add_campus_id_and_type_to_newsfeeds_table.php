<?php

use App\Enums\TargetPlayer;
use App\Models\Campus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('newsfeeds', function (Blueprint $table) {
            $table->foreignIdFor(Campus::class)->nullable()->constrained()->cascadeOnDelete();
            $table->integer('target_player')->nullable()->default(TargetPlayer::ALLSTUDENT);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newsfeeds', function (Blueprint $table) {
            //
        });
    }
};
