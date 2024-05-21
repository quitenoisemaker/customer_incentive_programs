<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Service\ReferralCodeGenerator;
use App\Http\Requests\UserStoreRequest;

class UserController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {

        $validatedData = $request->validated();

        if ($request->has('referral_code')) {
            # code...
        }
        $requestData = array_merge(
            $validatedData,
            [
                'referred_by' => $user_id,
                'referral_code' => (new ReferralCodeGenerator)->generate(),
                'referral_count' => User::incrementReferralCount()
            ]
        );
        User::create($request->validated());
        return 'test';
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
