<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Service\ReferralCodeGenerator;
use App\Http\Requests\UserStoreRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    /**
     * Create a new class instance.
     */
    public function __construct(public ReferralCodeGenerator $referralCodeGenerator)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {

        $validatedData = $request->validated();
        $referredBy = null;

        if ($request->has('referral_code')) {
            $referredBy = $this->referralCodeGenerator->getUserIdByReferralCodeAndIncrementCount($request->referral_code);
        }

        $requestData = array_merge(
            $validatedData,
            [
                'referred_by' => $referredBy,
                'referral_code' => $this->referralCodeGenerator->generate()
            ]
        );

        User::create($requestData);

        return response()->json([
            'message' => 'user created',
            'data' => null
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return response()->json([
            'message' => 'success',
            'data' => $user
        ], Response::HTTP_OK);
    }
}
