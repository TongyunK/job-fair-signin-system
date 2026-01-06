<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id()->comment('主键ID');
            $table->string('name', 200)->comment('活动名称');
            $table->text('description')->nullable()->comment('活动描述');
            $table->date('event_date')->comment('活动日期');
            $table->time('start_time')->nullable()->comment('开始时间');
            $table->time('end_time')->nullable()->comment('结束时间');
            $table->string('location', 255)->nullable()->comment('活动地点');
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft')->comment('活动状态：draft-草稿，published-已发布，ongoing-进行中，completed-已完成，cancelled-已取消');
            $table->integer('max_queue_number')->default(0)->comment('最大排队号数量（0表示不限制）');
            $table->integer('average_process_time')->default(2)->comment('平均处理时间（分钟）');
            $table->boolean('is_active')->default(true)->comment('是否激活（当前活动）');
            $table->json('settings')->nullable()->comment('活动设置（JSON格式，如：短信通知开关、排队规则等）');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
            
            $table->index(['status', 'is_active']);
            $table->index('event_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

