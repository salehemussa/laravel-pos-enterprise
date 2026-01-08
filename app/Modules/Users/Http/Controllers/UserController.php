<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Modules\Users\Http\Requests\StoreUserRequest;
use App\Modules\Users\Http\Requests\UpdateUserRequest;
use App\Modules\Users\Http\Resources\UserResource;
use App\Modules\Users\DTOs\CreateUserData;
use App\Modules\Users\DTOs\UpdateUserData;
use App\Modules\Users\Actions\CreateUserAction;
use App\Modules\Users\Actions\UpdateUserAction;
use App\Modules\Users\Actions\DeleteUserAction;
use App\Modules\Users\Repositories\Contracts\UserRepository;

class UserController extends Controller
{
    public function index(Request $request, UserRepository $users): JsonResponse
    {
        // Policy: who can view list?
        $this->authorize('viewAny', User::class);

        $perPage = (int) ($request->query('per_page', 15));

        return response()->json([
            'data' => UserResource::collection($users->paginate($perPage)),
        ]);
    }

    public function store(StoreUserRequest $request, CreateUserAction $createUser): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = $createUser->execute(CreateUserData::fromRequest($request));

        return response()->json([
            'message' => 'User created successfully.',
            'data' => new UserResource($user),
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return response()->json([
            'data' => new UserResource($user),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $updateUser): JsonResponse
    {
        $this->authorize('update', $user);

        // If you want unique email validation on update, handle it here or in rules using route param.
        // This is a clean spot to add it if needed.

        $updated = $updateUser->execute($user, UpdateUserData::fromRequest($request));

        return response()->json([
            'message' => 'User updated successfully.',
            'data' => new UserResource($updated),
        ]);
    }

    public function destroy(User $user, DeleteUserAction $deleteUser): JsonResponse
    {
        $this->authorize('delete', $user);

        $deleteUser->execute($user);

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
