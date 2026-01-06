<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->string('name', 100)->comment('姓名');
            $table->string('username', 50)->unique()->comment('用户名（登录账号）');
            $table->string('email', 255)->unique()->nullable()->comment('邮箱地址');
            $table->string('phone', 20)->nullable()->comment('手机号');
            $table->timestamp('email_verified_at')->nullable()->comment('邮箱验证时间');
            $table->string('password')->comment('密码（加密）');
            $table->enum('role', ['admin', 'staff', 'viewer'])->default('staff')->comment('角色：admin-管理员，staff-工作人员，viewer-查看者');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active')->comment('状态：active-激活，inactive-未激活，banned-已禁用');
            $table->string('avatar', 255)->nullable()->comment('头像URL');
            $table->text('remark')->nullable()->comment('备注信息');
            $table->timestamp('last_login_at')->nullable()->comment('最后登录时间');
            $table->string('last_login_ip', 45)->nullable()->comment('最后登录IP');
            $table->rememberToken()->comment('记住我令牌');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

