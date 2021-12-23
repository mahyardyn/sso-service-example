<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('permissions_category', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Create category permission
        $cat_id = DB::table('permissions_category')->insertGetId(
            [
                'name' => 'کاربران',
            ]
        );

        // create permissions
        DB::table('permissions')->insert(
            [
                [
                    'name' => 'create-user',
                    'display_name' => 'ایجاد کاربر',
                    'permission_category_id' => $cat_id
                ],
                [
                    'name' => 'edit-user',
                    'display_name' => 'ویرایش کاربر',
                    'permission_category_id' => $cat_id
                ],
                [
                    'name' => 'delete-user',
                    'display_name' => 'حذف کاربر',
                    'permission_category_id' => $cat_id
                ],
                [
                    'name' => 'manage-group',
                    'display_name' => 'مدیریت گروها',
                    'permission_category_id' => $cat_id
                ]
            ]
        );

        // Create group
        DB::table('roles')->insert(
            [
                'name' => 'مدیر سیستم',
                'permissions' => [
                    'create-user',
                    'edit-user',
                    'delete-user',
                    'manage-group',
                ]

            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mongodb')->dropIfExists('permissions_category');
    }
}
