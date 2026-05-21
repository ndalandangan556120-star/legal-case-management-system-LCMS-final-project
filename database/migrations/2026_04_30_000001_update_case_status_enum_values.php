<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `cases` MODIFY `status` ENUM('Open', 'In Progress', 'Scheduled', 'Closed') NOT NULL DEFAULT 'Open'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `cases` MODIFY `status` ENUM('Open', 'Pending', 'Closed') NOT NULL DEFAULT 'Open'");
    }
};
