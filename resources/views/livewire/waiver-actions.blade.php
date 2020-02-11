<div>
    <ul class="list-unstyled mb-0" style="font-size:85%">
        <li class="text-capitalize">
            @if ($updated)
                @icon('checkmark', 'text-success')
            @endif
            <em class="text-capitalize">{{ $status }}</em>
        </li>

        @if (! $ticket->latestWaiver)
            <li class="mb-2">
                <a
                    href="#" role="button" wire:click.prevent="send"
                    class="btn btn-sm btn-outline-primary"
                >
                    Send Waiver
                </a>
            </li>
        @endif

        @if ($ticket->latestWaiver)
            @can('update', $ticket->latestWaiver)
                <li>
                    <a href="https://app.hellosign.com/send/resendDocs/guid/{{ $ticket->latestWaiver->provider_agreement_id }}" target="_blank">edit ↗</a>
                </li>
            @endcan

            @unless ($updated)
                @if (auth()->user()->can('remind', $ticket->latestWaiver) && $ticket->latestWaiver->canBeReminded())
                    <li>
                        <a
                            href="#" role="button" wire:click.prevent="resend"
                        >resend</a>
                    </li>
                @endif

                @can('delete', $ticket->latestWaiver)
                    <li>
                        <a
                            onclick="event.preventDefault(); confirm('Are you sure you want to cancel this waiver?') || event.stopImmediatePropagation()"
                            href="#" role="button" wire:click="cancel"
                            class="text-danger"
                        >cancel</a>
                    </li>
                @endcan

                @if (auth()->user()->can('update', $ticket->latestWaiver) && ! $ticket->latestWaiver->isComplete())
                    <li>
                        <a
                            onclick="event.preventDefault(); confirm('Are you sure you want to mark this waiver completed?') || event.stopImmediatePropagation()"
                            href="#" role="button" wire:click="complete"
                            class="text-muted"
                        >complete</a>
                    </li>
                @endif
            @endunless
        @endif
    </ul>
</div>
