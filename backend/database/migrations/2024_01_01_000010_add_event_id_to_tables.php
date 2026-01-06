<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 为sign_ins表添加event_id字段
        Schema::table('sign_ins', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('id')->constrained('events')->onDelete('set null')->comment('活动ID（外键关联events表）');
            $table->index('event_id');
        });

        // 为queues表添加event_id字段
        Schema::table('queues', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('id')->constrained('events')->onDelete('set null')->comment('活动ID（外键关联events表）');
            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::table('sign_ins', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });

        Schema::table('queues', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};

