<?php
namespace App\Services;

use App\Events\RepaidEvent;
use App\Exceptions\LoanException;
use App\Models\Loan;
use App\Repositories\Loan\LoanInterface;
use App\Repositories\LoanRepayment\RepaymentInterface;
use Illuminate\Database\Eloquent\Model;
use mysql_xdevapi\Exception;

class LoanService extends BaseService
{
    protected $repaymentRepository;

    public function __construct(LoanInterface $repository, RepaymentInterface $repaymentRepository)
    {
        parent::__construct($repository);
        $this->repaymentRepository = $repaymentRepository;
    }

    /**
     * @param int $userId
     * @param array $loanData
     * @return mixed
     */
    public function userApply(int $userId, array $loanData)
    {
        $loanData['user_id'] = $userId;
        $loanData['frequency'] = Loan::FREQUENCY['weekly'];
        $loanData['process_status'] = Loan::PROCESS_STATUS['approved'];
        $loanData['repayment_completed'] = Loan::REPAYMENT_COMPLETED['not_yet'];

        return $this->repository->create($loanData);
    }

    /**
     * @param array $repaidData
     * @return Model
     */
    public function repaid(array $repaidData)
    {
        $repayment = $this->repaymentRepository->create($repaidData);

        event(new RepaidEvent($repayment));

        return $repayment;
    }

    public function calculateRepaymentProcess(int $loanId)
    {
        $loan = $this->repository->find($loanId);

        if (empty($loan)) {
            throw new LoanException(__.'loan.not_found', 400);
        }
        $totalRepaidAmount = $this->repaymentRepository->getTotalRepaidAmount($loanId);

        if ($totalRepaidAmount >= $loan->amount) {
            $this->repository->update(
                $loan,
                ['repayment_completed' => Loan::REPAYMENT_COMPLETED['yes']]
            );
        }
    }
}
