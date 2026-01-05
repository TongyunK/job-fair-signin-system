<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sign_ins', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->string('name', 100)->comment('姓名');
            $table->string('phone', 20)->index()->comment('手机号');
            $table->string('email', 255)->nullable()->comment('邮箱地址');
            $table->string('company', 255)->nullable()->comment('公司名称');
            $table->string('position', 255)->nullable()->comment('职位');
            $table->string('queue_number', 20)->nullable()->index()->comment('排队号码');
            $table->timestamp('signed_at')->nullable()->comment('签到时间');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sign_ins');
    }
};

