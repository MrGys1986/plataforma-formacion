<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('resources') && ! Schema::hasTable('digital_resources')) {
            Schema::rename('resources', 'digital_resources');
        }

        if (Schema::hasTable('notifications_log') && ! Schema::hasTable('notification_logs')) {
            Schema::rename('notifications_log', 'notification_logs');
        }
    }

    public function down(): void
    {
        //
    }
};
