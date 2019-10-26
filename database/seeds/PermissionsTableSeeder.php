<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->truncate();

        // CRUD posts
        $crudPost = new Permission();
        $crudPost->name = "crud-post";
        $crudPost->save();

        // Update others post
        $updateOthersPost = new Permission();
        $updateOthersPost->name = "update-others-post";
        $updateOthersPost->save();

        // Delete others post
        $deleteOthersPost = new Permission();
        $deleteOthersPost->name = "delete-others-post";
        $deleteOthersPost->save();

        // CRUD category
        $crudCategory = new Permission();
        $crudCategory->name = "crud-category";
        $crudCategory->save();

        // CRUD user
        $crudUser = new Permission();
        $crudUser->name = "crud-user";
        $crudUser->save();

        // Attach roles permissions
        $admin = Role::whereName('admin')->first();
        $editor = Role::whereName('editor')->first();
        $author = Role::whereName('author')->first();

        $admin->detachPermissions([$crudPost, $updateOthersPost, $deleteOthersPost, $crudCategory, $crudUser]);
        $admin->attachPermissions([$crudPost, $updateOthersPost, $deleteOthersPost, $crudCategory, $crudUser]);
        
        $editor->detachPermissions([$crudPost, $updateOthersPost, $deleteOthersPost, $crudCategory]);
        $editor->attachPermissions([$crudPost, $updateOthersPost, $deleteOthersPost, $crudCategory]);
        
        $author->detachPermission($crudPost);
        $author->attachPermission($crudPost);
    }
}
