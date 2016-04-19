<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\TransactionSplit;
use Illuminate\Http\Request;
use App\Repositories\TransactionRepository;

class TransactionController extends Controller
{
    protected $transactions;

    public function __construct(TransactionRepository $transactions)
    {
        $this->authorize('owner', request()->transaction->order);

        $this->transactions = $transactions;
    }

    public function refund(TransactionSplit $transaction)
    {
        return view('transaction.refund')->withTransaction($transaction);
    }

    public function storeRefund(Request $request, TransactionSplit $transaction)
    {
        try {
            $this->transactions->refund($transaction, $request->amount);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withError($e->getMessage());
        }

        return redirect()->route('order.show', $transaction->order);
    }

    public function edit(TransactionSplit $transaction)
    {
        $transactionData = [
            'amount' => $transaction->amount,
            'processor_transactionid' => $transaction->transaction->processor_transactionid
        ];

        return view('transaction.edit', compact('transactionData'))->withTransaction($transaction);
    }

    public function update(Request $request, TransactionSplit $transaction)
    {
        $transaction->forceFill([
            'amount' => $request->amount,
        ])->save();

        $transaction->transaction->forceFill([
            'amount' => $request->amount,
            'processor_transactionid' => $request->processor_transactionid,
        ])->save();

        return redirect()->route('order.show', $transaction->order);
    }

    public function delete(TransactionSplit $transaction)
    {
        $order = $transaction->order;

        $transaction->delete();

        return redirect()->route('order.show', $order);
    }
}
