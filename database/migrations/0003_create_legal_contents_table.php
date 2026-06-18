<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_contents', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->index();
            $table->string('locale', 10)->default('de');
            $table->string('title');
            $table->text('content');
            $table->boolean('published')->default(true);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['key', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_contents');
    }
};
