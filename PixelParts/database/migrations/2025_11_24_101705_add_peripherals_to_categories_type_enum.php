<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the ENUM column to add 'peripheral'
        DB::statement("ALTER TABLE categories MODIFY COLUMN type ENUM('laptop', 'component', 'peripheral') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'peripheral' from the ENUM
        DB::statement("ALTER TABLE categories MODIFY COLUMN type ENUM('laptop', 'component') NOT NULL");
    }
};
