<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\TransactionSplit;
use Illuminate\Http\Request;
use App\Repositories\TransactionRepository;

class TransactionController extends Controller
{
    public function edit(TransactionSplit $split)
    {
        request()->intended(url()->previous());

        $this->authorize('edit', $split->order);

        return view('transaction.edit')->withTransaction($split);
    }

    public function update(TransactionSplit $split)
    {
        $this->authorize('owner', $split->order);

        $this->validate(request(), [
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

        return redirect()->intended(action('OrderController@show', $split->order))->withSucess('Transaction updated.');
    }

    public function delete(TransactionSplit $split)
    {
        $this->authorize('edit', $split->order);

        $split->delete();

        return redirect()->back()->withSuccess('Transaction deleted');
    }
}
