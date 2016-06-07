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
        $this->transactions = $transactions;

        $this->middleware('admin');
    }

    public function refund(TransactionSplit $transaction)
    {
        $this->authorize('owner', $transaction->order);

        return view('transaction.refund')->withTransaction($transaction);
    }

    public function storeRefund(Request $request, TransactionSplit $transaction)
    {
        $this->authorize('owner', $transaction->order);

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
        $this->authorize('owner', $transaction->order);

        $transactionData = [
            'amount' => $transaction->amount,
            'processor_transactionid' => $transaction->transaction->processor_transactionid
        ];

        return view('transaction.edit', compact('transactionData'))->withTransaction($transaction);
    }

    public function update(Request $request, TransactionSplit $transaction)
    {
        $this->authorize('owner', $transaction->order);

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
        $this->authorize('owner', $transaction->order);

        $order = $transaction->order;

        $transaction->delete();

        return redirect()->route('order.show', $order);
    }
}
