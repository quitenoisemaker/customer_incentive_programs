<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Service\ReferralCodeGenerator;
use App\Http\Requests\UserStoreRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserReferralProgramController extends Controller
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
    public function store(UserStoreRequest $request): JsonResponse
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
     * fetch user referrals.
     */
    public function fetchUserReferral(User $user): JsonResponse
    {
        $referrals = User::where('referred_by', $user->id)->get();

        return response()->json([
            'message' => 'success',
            'data' => [
                'referrals_count' => $user->referral_count,
                'referrals' => $referrals
            ]
        ], Response::HTTP_OK);
    }
}
