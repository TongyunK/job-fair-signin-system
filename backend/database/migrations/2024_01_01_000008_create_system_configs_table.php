<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_configs', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->string('key', 100)->unique()->comment('配置键名（唯一）');
            $table->string('name', 100)->comment('配置名称');
            $table->text('value')->nullable()->comment('配置值');
            $table->string('type', 50)->default('string')->comment('配置类型：string-字符串，integer-整数，boolean-布尔值，json-JSON，text-文本');
            $table->text('description')->nullable()->comment('配置说明');
            $table->string('group', 50)->default('general')->comment('配置分组（如：general-通用，queue-排队，sms-短信等）');
            $table->boolean('is_public')->default(false)->comment('是否公开（前端可访问）');
            $table->integer('sort_order')->default(0)->comment('排序顺序');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            
            $table->index('group');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_configs');
    }
};

