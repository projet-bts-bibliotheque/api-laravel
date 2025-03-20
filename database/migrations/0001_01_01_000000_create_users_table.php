<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration pour créer les tables d'authentification et de session
 */
return new class extends Migration
{
    /**
     * Exécute les migrations pour créer les tables
     * - users: stocke les informations utilisateur
     * - password_reset_tokens: gère la réinitialisation de mot de passe
     * - sessions: stocke les informations de session
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();  // L'email doit être unique
            $table->string('password');
            $table->string('address');
            $table->string('phone');

            $table->tinyInteger('role')->default(0);  // 0=utilisateur, 1=staff, 2+=admin
            $table->timestamp('email_verified_at')->nullable();  // Null si email non vérifié
            
            $table->rememberToken();  // Pour la fonctionnalité "Se souvenir de moi"
            $table->timestamps();  // created_at et updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();  // L'email est la clé primaire
            $table->string('token');  // Token unique pour la réinitialisation
            $table->timestamp('created_at')->nullable();  // Pour l'expiration des tokens
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();  // ID unique de session
            $table->foreignId('user_id')->nullable()->index();  // Référence à l'utilisateur
            $table->string('ip_address', 45)->nullable();  // Support IPv6
            $table->text('user_agent')->nullable();  // Navigateur/appareil
            $table->longText('payload');  // Données de session
            $table->integer('last_activity')->index();  // Pour l'expiration des sessions
        });
    }

    /**
     * Annule les migrations en supprimant les tables
     * dans l'ordre inverse de leur création
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};