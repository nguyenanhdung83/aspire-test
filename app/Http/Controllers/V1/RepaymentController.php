<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Resources\RepaymentResource;
use App\Services\RepaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RepaymentController extends BaseController
{
    protected $repaymentService;

    public function __construct(RepaymentService $repaymentService)
    {
        $this->repaymentService = $repaymentService;
    }

    /**
     * Show detail a repayment
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        $repayment = $this->repaymentService->detail($id, $request->user()->id);

        return $this->responseSuccess(new RepaymentResource($repayment));
    }
}
