<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('操作用户ID（外键关联users表，可为空表示系统操作）');
            $table->string('action', 100)->comment('操作类型（如：sign_in, get_queue, update_status等）');
            $table->string('module', 50)->comment('操作模块（如：queue, sign_in, user等）');
            $table->text('description')->comment('操作描述');
            $table->string('ip_address', 45)->nullable()->comment('IP地址');
            $table->string('user_agent', 500)->nullable()->comment('用户代理（浏览器信息）');
            $table->json('request_data')->nullable()->comment('请求数据（JSON格式）');
            $table->json('response_data')->nullable()->comment('响应数据（JSON格式）');
            $table->enum('status', ['success', 'failed', 'warning'])->default('success')->comment('操作状态：success-成功，failed-失败，warning-警告');
            $table->text('error_message')->nullable()->comment('错误信息（如果操作失败）');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            
            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'action']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operation_logs');
    }
};

