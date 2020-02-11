<?php

namespace App\Http\Livewire;

use App\Ticket;
use App\Waiver;
use Livewire\Component;
use App\Jobs\Waiver\SendReminder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WaiverActions extends Component
{
    use AuthorizesRequests;

    public $ticketId;

    public $updated = false;

    public $status;

    public $sent = false;

    public function mount($ticket)
    {
        $this->ticketId = $ticket->id;
        $this->status = $ticket->status;
    }

    public function ticket(): Ticket
    {
        return Ticket::query()
            ->resolveLatestWaiver()
            ->whereKey($this->ticketId)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.waiver-actions', [
            'ticket' => $this->ticket(),
        ]);
    }

    public function send()
    {
        $this->authorize('update', $this->ticket());

        $this->ticket()->createWaiver();

        $this->sent = true;
    }

    public function resend()
    {
        $this->authorize('remind', $this->ticket()->latestWaiver);

        if (! $this->ticket()->latestWaiver->canBeReminded()) {
            abort(403, 'This waiver cannot be reminded.');
        }

        SendReminder::dispatch($this->ticket()->latestWaiver);

        $this->updateStatus('refreshing');
    }

    public function cancel()
    {
        $this->authorize('delete', $this->ticket()->latestWaiver);

        $this->ticket()->latestWaiver->delete();

        $this->updateStatus('canceled');
    }

    public function complete()
    {
        $this->authorize('update', $this->ticket());

        $this->ticket()->waivers->each->delete();

        $this->ticket()->waivers()->save(
            Waiver::completedOffline()
        );

        $this->updateStatus('completed');
    }

    protected function updateStatus($status)
    {
        $this->updated = true;
        $this->status = $status;
    }
}
