<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS auth');
        DB::statement('CREATE SCHEMA IF NOT EXISTS userdata');
    }

    public function down(): void
    {
        DB::statement('DROP SCHEMA IF EXISTS auth CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS userdata CASCADE');
    }
};
