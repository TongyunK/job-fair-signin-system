<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->foreignId('sign_in_id')->constrained('sign_ins')->onDelete('cascade')->comment('签到记录ID（外键关联sign_ins表）');
            $table->string('queue_number', 20)->unique()->index()->comment('排队号码');
            $table->enum('status', ['waiting', 'processing', 'completed', 'cancelled'])->default('waiting')->index()->comment('排队状态：waiting-等待中，processing-处理中，completed-已完成，cancelled-已取消');
            $table->timestamp('started_at')->nullable()->comment('开始处理时间');
            $table->timestamp('completed_at')->nullable()->comment('完成时间');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};

