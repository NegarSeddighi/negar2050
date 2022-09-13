<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        if ($request->filled('name') &&  $request->filled('email')) {
            $users1 = User::search($request->name)->select('name')->paginate();
            $users2 = User::search($request->email)->select('email')->paginate();
            $users = $users1->concat($users2)->unique();
        } elseif ($request->filled('name')) {
            $users = User::search($request->name)->select('name')->paginate();
        } else {
            $users = User::search($request->email)->select('email')->paginate();
        }
 
        return response()->json(UserCollection::make($users), 
        Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "is_active" => $request->is_active
        ]);

        $permissions = auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray();

        if ($request->is_admin == 1 && in_array("store admin", $permissions)) {
            $user->assignRole('admin');
        }
        
        return UserResource::make($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $permissions = auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray();

        if (in_array("show", $permissions) || ($user->id == auth()->user()->id)) {
            return UserResource::make($user);
        }

        return response()->json([
            'message' => "You don't have permission to view this user",
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $permissions = auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray();

        if (in_array("update", $permissions) || ($user->id == auth()->user()->id)) {
            if (auth()->user()->hasRole('admin') && !$user->hasRole('admin') || ($user->id == auth()->user()->id)) {
                $user->update([
                    "name" => $request->name,
                    "password" => bcrypt($request->password)
                ]);
            }
         
            if (in_array("update admin", $permissions)) {
                if ($request->is_admin == 1) {
                    $user->assignRole('admin');
                }
                if ($request->is_admin == 0) {
                    $user->removeRole('admin');
                }
                $user->update([
                    "is_active" => $request->is_active,
                ]);

            }

            if (auth()->user()->hasRole('admin') && !$user->hasRole('admin')) {
                $user->update([
                    "is_active" => $request->is_active,
                ]); 
            }
            
            return UserResource::make($user);
        }

        return response()->json([
            'message' => "You don't have permission to update this user",
        ], Response::HTTP_FORBIDDEN);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $permissions = auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray();

        if (!in_array("destroy admin", $permissions) && ($user->hasRole(['admin', 'manager']))) {

            return response()->json([
                'message' => "You don't have permission to delete this user",
            ], Response::HTTP_FORBIDDEN);

        } else {

            $user->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
   
    }
}
