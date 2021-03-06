<?php


namespace App\Repositories;


use App\Contracts\DataTransactionContract;
use App\DataTransaction;
use App\Enums\TransactionStatus;

class DataTransactionRepository implements DataTransactionContract
{

    /**
     * @param array $dataTransaction
     * @return DataTransaction
     */
    public function create(array $dataTransaction): DataTransaction
    {
        return DataTransaction::create($dataTransaction);
    }

    /**
     * @param string $transaction_id
     * @return DataTransaction
     */
    public function mark_transaction_successful(string $transaction_id): DataTransaction
    {

        $transaction = DataTransaction::findOrFail($transaction_id);

        if($transaction->status === TransactionStatus::COMPLETED){
            return $transaction;
        }


        $transaction->status = TransactionStatus::COMPLETED;
        $transaction->save();
        return $transaction;
    }

    /**
     * @param string $transaction_id
     * @return DataTransaction
     */
    public function mark_transaction_failed(string $transaction_id): DataTransaction
    {
        $transaction = DataTransaction::findOrFail($transaction_id);
        $transaction->status = TransactionStatus::FAILED;
        $transaction->save();
        return $transaction;
    }
}
