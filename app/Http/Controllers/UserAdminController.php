<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAdminRequest;
use App\Lib\Logger\Logger;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class UserAdminController extends Controller
{
    public function create(UserAdminRequest $request): JsonResponse
    {
        // Get validated values
        $fields = $request->validated();

        // Mobile fix
        $fields['mobile'] = (int)$fields['mobile'];

        // Create new User object
        $user = new User();

        // set data into object
        foreach ($user->getFillable() as $key)
            if (isset($fields[$key]))
                $user->$key = $fields[$key];

        // Generate log
        Logger::set('info', 'create', 'User created successfully');

        // Change default locale to json locale
        $this->setDefaultLocale();

        // Return result to client
        return new JsonResponse(['success' => true, 'user' => $user->save(), 'message' => __('User created successfully')]);
    }

    public function edit(UserAdminRequest $request): JsonResponse
    {
        // Get validated values
        $fields = $request->all();

        // Mobile fix
        if (isset($fields['mobile']))
            $fields['mobile'] = (int)$fields['mobile'];

        // User
        $user = User::findOrFail($fields['_id']);

        // Get fillable
        $fillable = $user->getFillable();

        // Check manage-group permission for set role
        if ($request::$manageGroup) {
            $fillable[] = 'role';

            // Generate log
            Logger::set('info', 'change-permission', 'User permission changed successfully');
        }

        // set data
        foreach ($fillable as $key)
            if (isset($fields[$key]))
                $user->$key = $fields[$key];;

        // Generate log
        Logger::set('info', 'edit', 'User updated successfully');

        // Change default locale to json locale
        $this->setDefaultLocale();

        // Return result to client
        return new JsonResponse(['success' => true, 'user' => $user->save(), 'message' => __('User updated successfully')]);
    }

    public function delete(UserAdminRequest $request): JsonResponse
    {
        // User
        $user = User::findOrFail($request->input('_id'));

        // Generate log
        Logger::set('info', 'delete', 'User deleted successfully');

        // Change default locale to json locale
        $this->setDefaultLocale();

        // Return result to client
        return new JsonResponse(['success' => true, 'user' => $user->delete(), 'message' => __('User deleted successfully')]);
    }

    /**
     * Change default locale to json locale
     *
     * @return void
     */
    protected function setDefaultLocale(): void
    {
        app('translator')->setLocale(config('app.json_locale'));
    }
}
