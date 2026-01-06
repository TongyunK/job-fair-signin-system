<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->foreignId('sign_in_id')->nullable()->constrained('sign_ins')->onDelete('set null')->comment('签到记录ID（外键关联sign_ins表）');
            $table->string('phone', 20)->comment('接收短信的手机号');
            $table->string('queue_number', 20)->nullable()->comment('排队号码');
            $table->text('content')->comment('短信内容');
            $table->enum('type', ['queue_notification', 'reminder', 'status_update', 'other'])->default('queue_notification')->comment('短信类型：queue_notification-排队通知，reminder-提醒，status_update-状态更新，other-其他');
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending')->comment('发送状态：pending-待发送，sent-已发送，failed-发送失败，delivered-已送达');
            $table->string('provider', 50)->default('twilio')->comment('短信服务提供商（如：twilio）');
            $table->string('provider_message_id', 100)->nullable()->comment('服务商返回的消息ID');
            $table->text('provider_response')->nullable()->comment('服务商返回的响应（JSON格式）');
            $table->text('error_message')->nullable()->comment('错误信息（如果发送失败）');
            $table->timestamp('sent_at')->nullable()->comment('发送时间');
            $table->timestamp('delivered_at')->nullable()->comment('送达时间');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            
            $table->index(['sign_in_id', 'created_at']);
            $table->index(['phone', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};

