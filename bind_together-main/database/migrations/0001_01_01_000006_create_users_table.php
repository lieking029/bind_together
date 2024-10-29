<?php

use App\Models\Campus;
use App\Models\Course;
use App\Models\Organization;
use App\Models\Program;
use App\Models\Sport;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('student_number')->nullable();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('suffix')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('gender');
            $table->string('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->unique();
            $table->integer('year_level')->nullable();
            $table->foreignIdFor(Sport::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Course::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Campus::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Program::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Organization::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
