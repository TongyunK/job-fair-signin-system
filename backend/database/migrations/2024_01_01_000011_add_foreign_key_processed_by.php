<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 为queues表的processed_by字段添加外键约束
        if (Schema::hasTable('users') && Schema::hasColumn('queues', 'processed_by')) {
            Schema::table('queues', function (Blueprint $table) {
                $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('queues', 'processed_by')) {
            Schema::table('queues', function (Blueprint $table) {
                $table->dropForeign(['processed_by']);
            });
        }
    }
};

