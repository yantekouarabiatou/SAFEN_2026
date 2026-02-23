<?php

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
        // Vérifier si les colonnes existent déjà avant de les ajouter
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'status')) {
                $table->string('status')->default('pending')->after('comment');
            }
            
            if (!Schema::hasColumn('reviews', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('reviews', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('rejection_reason');
            }
            
            if (!Schema::hasColumn('reviews', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            }
            
            if (!Schema::hasColumn('reviews', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_by');
            }
            
            if (!Schema::hasColumn('reviews', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            }
        });

        // Ajouter les clés étrangères si les colonnes existent
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'approved_by') && !$this->foreignKeyExists('reviews', 'reviews_approved_by_foreign')) {
                $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            }
            
            if (Schema::hasColumn('reviews', 'rejected_by') && !$this->foreignKeyExists('reviews', 'reviews_rejected_by_foreign')) {
                $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Supprimer les clés étrangères d'abord
            if ($this->foreignKeyExists('reviews', 'reviews_approved_by_foreign')) {
                $table->dropForeign('reviews_approved_by_foreign');
            }
            
            if ($this->foreignKeyExists('reviews', 'reviews_rejected_by_foreign')) {
                $table->dropForeign('reviews_rejected_by_foreign');
            }
            
            // Supprimer les colonnes
            $columns = [
                'status',
                'rejection_reason',
                'approved_at',
                'approved_by',
                'rejected_at',
                'rejected_by'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('reviews', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Vérifier si une clé étrangère existe
     */
    private function foreignKeyExists($table, $foreignKeyName)
    {
        // Pour MySQL
        $result = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = ?
            AND CONSTRAINT_NAME = ?
        ", [$table, $foreignKeyName]);

        return !empty($result);
    }
};