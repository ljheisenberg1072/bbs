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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index()->comment('帖子标题');
            $table->text('body')->comment('帖子内容');
            $table->unsignedInteger('reply_count')->default(0)->comment('回复数量');
            $table->unsignedInteger('view_count')->default(0)->comment('查看总数');
            $table->unsignedInteger('order')->default(0)->comment('排序');
            $table->text('excerpt')->nullable()->comment('');
            $table->string('slug')->nullable()->comment('');
            $table->unsignedBigInteger('user_id')->comment('用户ID');
            $table->unsignedBigInteger('category_id')->comment('分类ID');
            $table->unsignedBigInteger('last_reply_user_id')->default(0)->comment('用户ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
