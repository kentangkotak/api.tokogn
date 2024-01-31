<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function listuser()
    {
        $listuser = User::paginate(request('per_page'));
        return new JsonResponse($listuser);
    }
}
