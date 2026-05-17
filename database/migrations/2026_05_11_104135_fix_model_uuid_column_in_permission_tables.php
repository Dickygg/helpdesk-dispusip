<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // === model_has_roles ===
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropPrimary();
        });
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->char('model_uuid', 36)->change();
            $table->primary(['role_id', 'model_type', 'model_uuid']);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        // === model_has_permissions ===
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropForeign(['permission_id']);
        });
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropPrimary();
        });
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->char('model_uuid', 36)->change();
            $table->primary(['permission_id', 'model_type', 'model_uuid']);
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropPrimary();
        });
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('model_uuid')->change();
            $table->primary(['role_id', 'model_type', 'model_uuid']);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropForeign(['permission_id']);
            $table->dropPrimary();
        });
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('model_uuid')->change();
            $table->primary(['permission_id', 'model_type', 'model_uuid']);
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }
};
