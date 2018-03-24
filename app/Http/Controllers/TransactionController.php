<?php

namespace App\Http\Controllers;

use App\TransactionSplit;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit(TransactionSplit $split)
    {
        request()->intended(url()->previous());

        $this->authorize('update', $split->order);

        return view('transaction.edit')->withTransaction($split);
    }

    public function update(TransactionSplit $split)
    {
        $this->authorize('update', $split->order);

        request()->validate([
            'amount' => 'required',
            'identifier' => 'required',
        ]);

        $split->update([
            'amount' => request('amount') * 100
        ]);

        $split->transaction->update([
            'amount' => request('amount') * 100,
            'identifier' => request('identifier'),
        ]);

        return redirect()->intended(action('OrderController@show', $split->order))
            ->withSucess('Transaction updated.');
    }

    public function delete(TransactionSplit $split)
    {
        $this->authorize('update', $split->order);

        $split->delete();

        return redirect()->back()->withSuccess('Transaction deleted');
    }
}
