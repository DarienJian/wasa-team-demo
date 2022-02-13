<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserGroup\SetGroupRequest;
use App\Models\UserGroup;

class UserGroupController extends Controller
{
    public function setGroup(SetGroupRequest $request) {
        $validated = $request->validated();
        return UserGroup::where('user_id', $validated['user_id'])->update([
            'group_id' => $validated['group_id']
        ]);
    }
}
