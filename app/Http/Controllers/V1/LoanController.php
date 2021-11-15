<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\API\V1\BaseController;
use App\Http\Requests\Loan\LoanCreateRequest;
use App\Http\Requests\Loan\LoanShowRequest;
use App\Http\Requests\Repayment\RepaidRequest;
use App\Http\Resources\RepaymentResource;
use App\Http\Resources\LoanResource;
use App\Services\LoanService;
use App\Services\RepaymentService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoanController extends BaseController
{
    protected $loanService;

    protected $repaymentService;

    public function __construct(LoanService $loanService, RepaymentService $repaymentService)
    {
        $this->loanService = $loanService;
        $this->repaymentService = $repaymentService;
    }

    /**
     * Get all user's loans
     */
    public function index(Request $request): JsonResponse
    {
        $loans = $this->loanService->list($request->user()->id);

        return $this->responseSuccess(LoanResource::collection($loans));
    }

    /**
     * Create a loan
     * @param LoanCreateRequest $request
     * @return JsonResponse
     */
    public function store(LoanCreateRequest $request): JsonResponse
    {
        try {
            $loanData = $request->validated();
            $loan = $this->loanService->userApply($request->user()->id, $loanData);

            return $this->responseSuccess(new LoanResource($loan));
        } catch (\Exception $e) {
            //mail|log original exception for tracking
            report($e);
            //just return to api common error
            return $this->responseError(__('errors.099'),[], 99);
        }
    }

    /**
     * Show detail a loan
     * @param LoanShowRequest $request
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(LoanShowRequest $request, $id): JsonResponse
    {
        $loan = $this->loanService->detail($id, $request->user()->id);

        if (empty($loan)) {
            return $this->responseError(__('loan.not_found'));
        }

        return $this->responseSuccess(new LoanResource($loan));
    }

    /**
     * Get list loan repayments
     * @param LoanShowRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function repayments(LoanShowRequest $request, $id): JsonResponse
    {
        $repayments = $this->repaymentService->getByLoanId($id, $request->user()->id);

        return $this->responseSuccess(RepaymentResource::collection($repayments));
    }

    /**
     * User repaid loan
     * @param RepaidRequest $request
     * @param $id
     * @return mixed
     */
    public function repaid(RepaidRequest $request, $id)
    {
        $repaidData = $request->validated();

        try {
            $repayment = $this->loanService->repaid($repaidData);

            return $this->responseSuccess(new RepaymentResource($repayment));
        } catch (\Exception $e) {
            report($e);

            return $this->responseError(__('errors.099'),[], 99);
        }
    }
}
