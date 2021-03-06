<?php


namespace App\Repositories;


use App\Contracts\ElectricityTransactionContract;
use App\ElectricityTransaction;
use App\Enums\TransactionStatus;
use App\PowerPlanList;

class ElectricityTransactionRepository implements ElectricityTransactionContract
{

    /**
     * @param array $electricityTransaction
     * @return ElectricityTransaction
     */
    public function create(array $electricityTransaction): ElectricityTransaction
    {
        $plan_data = PowerPlanList::find($electricityTransaction["plan"]);
        $electricityTransaction['plan'] = $plan_data->description;
        return ElectricityTransaction::create($electricityTransaction);

    }

    /**
     * @param string $transaction_id
     * @return ElectricityTransaction
     */
    public function mark_transaction_successful(string $transaction_id): ElectricityTransaction
    {

        $transaction = ElectricityTransaction::findOrFail($transaction_id);

        if ($transaction->status === TransactionStatus::COMPLETED) {
            return $transaction;
        }

        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;

    }

    /**
     * @param string $transaction_id
     * @return ElectricityTransaction
     */
    public function mark_transaction_failed(string $transaction_id): ElectricityTransaction
    {
        $transaction = ElectricityTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}
