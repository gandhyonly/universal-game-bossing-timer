<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timers', function (Blueprint $table) {
            $table->boolean('notified_two_min')->default(false)->after('spawn_at');
        });
    }

    public function down(): void
    {
        Schema::table('timers', function (Blueprint $table) {
            $table->dropColumn('notified_two_min');
        });
    }
};


