<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\PermissionRegistrar;

class AddLocationPermissions extends Migration
{
    public function up()
    {
        if (app()->has(Permission::class)) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            foreach ([
                         'view locations',
                         'create locations',
                         'update locations',
                         'delete locations',
                         'view markets',
                         'create markets',
                         'update markets',
                         'delete markets'
                     ] as $name) {
                app(Permission::class)::findOrCreate($name, null);
            };
        }
    }
}
