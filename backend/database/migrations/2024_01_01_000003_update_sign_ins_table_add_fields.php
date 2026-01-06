<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sign_ins', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('signed_at')->comment('IP地址');
            $table->string('user_agent', 500)->nullable()->after('ip_address')->comment('用户代理（浏览器信息）');
            $table->enum('device_type', ['mobile', 'pc', 'tablet', 'unknown'])->default('unknown')->after('user_agent')->comment('设备类型：mobile-移动端，pc-PC端，tablet-平板，unknown-未知');
            $table->text('remark')->nullable()->after('device_type')->comment('备注信息');
            $table->enum('source', ['web', 'api', 'admin'])->default('web')->after('remark')->comment('签到来源：web-网页，api-API接口，admin-后台管理');
        });
    }

    public function down(): void
    {
        Schema::table('sign_ins', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'user_agent', 'device_type', 'remark', 'source']);
        });
    }
};

