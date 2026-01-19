<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Converts the ENUM columns in roles and permissions tables to VARCHAR
     * to allow dynamic addition of new roles/permissions without migrations.
     *
     * This migration only runs on PostgreSQL as it's converting Postgres ENUM types.
     * SQLite and other databases that didn't have real ENUMs don't need this.
     */
    public function up(): void
    {
        // Only run on PostgreSQL - other databases don't have real ENUM types
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        $tableNames = config('permission.table_names');

        // Convert permissions.name from enum to varchar
        DB::statement("ALTER TABLE {$tableNames['permissions']} ALTER COLUMN name TYPE VARCHAR(255)");

        // Convert roles.name from enum to varchar
        DB::statement("ALTER TABLE {$tableNames['roles']} ALTER COLUMN name TYPE VARCHAR(255)");

        // Drop the enum types (Postgres creates these automatically for enum columns)
        DB::statement('DROP TYPE IF EXISTS permissions_name');
        DB::statement('DROP TYPE IF EXISTS roles_name');
    }

    /**
     * Reverse the migrations.
     *
     * Note: Rolling back will NOT restore the enum constraints.
     * If you need the enum constraints back, restore from backup or recreate the tables.
     */
    public function down(): void
    {
        // Converting back to enum would require knowing all valid values
        // and ensuring existing data matches. This is intentionally left empty
        // as it's a one-way migration for practical purposes.
    }
};
