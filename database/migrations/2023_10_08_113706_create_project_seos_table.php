<?php

use App\Enums\Database\Project\SeoProjectDesignBy;
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
        Schema::create('project_seo', function (Blueprint $table) {
            $table->id();
            $table->string('field_activity')->nullable();

            $table->json('host');

            $table->date('agreement_at');
            $table->unsignedInteger('agreement_duration')
                ->default(0)
                ->comment('per days');

            $table->string('amount_content')
                ->nullable();

            $table->unsignedTinyInteger('keywords_count')
                ->default(0);

            $table->text('keywords');

            $table->unsignedInteger('price_monthly');

            $table->unsignedTinyInteger('due_date_payments');

            $table->unsignedTinyInteger('designed_by')
                ->default(SeoProjectDesignBy::ByCompany);

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_seo');
    }
};
