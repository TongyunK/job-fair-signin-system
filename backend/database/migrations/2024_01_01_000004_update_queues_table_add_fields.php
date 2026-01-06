<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            $table->integer('estimated_wait_time')->nullable()->after('status')->comment('预计等待时间（分钟）');
            $table->integer('actual_wait_time')->nullable()->after('estimated_wait_time')->comment('实际等待时间（分钟）');
            $table->unsignedBigInteger('processed_by')->nullable()->after('actual_wait_time')->comment('处理人员ID（外键关联users表，将在users表创建后添加外键约束）');
            $table->text('note')->nullable()->after('processed_by')->comment('处理备注');
        });
    }

    public function down(): void
    {
        Schema::table('queues', function (Blueprint $table) {
            if (Schema::hasColumn('queues', 'processed_by')) {
                // 先删除外键约束（如果存在）
                try {
                    $table->dropForeign(['processed_by']);
                } catch (\Exception $e) {
                    // 忽略外键不存在的错误
                }
            }
            $table->dropColumn(['estimated_wait_time', 'actual_wait_time', 'processed_by', 'note']);
        });
    }
};

