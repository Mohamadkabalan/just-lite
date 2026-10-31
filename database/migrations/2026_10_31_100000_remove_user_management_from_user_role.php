<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The Voyager "user" role could browse, edit, add and delete users, which let
 * it change another user's role or password and so make itself admin. It keeps
 * the site content, contact messages and bookings; user management stays with
 * the admin role. Only the role/permission link table changes.
 */
return new class extends Migration
{
    private const PERMISSIONS = ['browse_users', 'read_users', 'edit_users', 'add_users', 'delete_users'];

    public function up(): void
    {
        $roleId = DB::table('roles')->where('name', 'user')->value('id');

        if ($roleId === null) {
            return;
        }

        DB::table('permission_role')
            ->where('role_id', $roleId)
            ->whereIn('permission_id', DB::table('permissions')->whereIn('key', self::PERMISSIONS)->pluck('id'))
            ->delete();
    }

    public function down(): void
    {
        $roleId = DB::table('roles')->where('name', 'user')->value('id');

        if ($roleId === null) {
            return;
        }

        foreach (DB::table('permissions')->whereIn('key', self::PERMISSIONS)->pluck('id') as $permissionId) {
            DB::table('permission_role')->insertOrIgnore(['permission_id' => $permissionId, 'role_id' => $roleId]);
        }
    }
};
