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
        Schema::table('users', function (Blueprint $table) {
            $table->string('weapp_openid')->nullable()->unique()->after('weixin_openid');
            $table->string('weixin_session_key')->nullable()->after('weapp_openid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('weapp_openid');
            $table->dropColumn('weixin_session_key');
        });
    }
};
