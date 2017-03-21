@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
    <header class="d-flex justify-content-between mb-5">
        <h1>{{ $organization->church->name }}</h1>
        <p>
            @if (auth()->user()->isSuperAdmin())
                <a href="{{ action('Super\OrganizationPaymentController@index', $organization) }}" class="btn btn-primary">Add Payment</a>
                <a href="{{ action('Super\OrganizationItemController@create', $organization) }}" class="btn btn-secondary">Add Item</a>
            @endif
            <a class="btn btn-secondary" href="{{ action('Super\OrganizationController@edit', $organization) }}">Edit</a>
        </p>
    </header>

        {{--
        <div class="card-deck">
            <div class="card mb-3 text-center">
                <div class="card-block"><h3>{{ $organization->attendees->active()->count() }}</h3></div>
                <div class="card-footer text-muted">Registered</div>
            </div>
            <div class="card mb-3 text-center">
                <div class="card-block"><h3>{{ $organization->signed_waivers_count }}</h3></div>
                <div class="card-footer text-muted">Signed Waivers</div>
            </div>
            <div class="card mb-3 text-center">
                <div class="card-block"><h3>{{ $organization->rooms->count() }}</h3></div>
                <div class="card-footer text-muted">Rooms</div>
            </div>
            <div class="card mb-3 text-center">
                <div class="card-block"><h3>{{ $organization->assigned_to_room_count }}</h3></div>
                <div class="card-footer text-muted">Assigned</div>
            </div>
        </div>
        --}}

        <div class="row">
            <div class="col-lg-8 mb-3">
                @include('organization/partials/billing_summary')
            </div>
        </div>

        <hr>

        <div class="card-deck">
            <div class="card mb-3">
                <h4 class="card-header">Church</h4>
                <div class="card-block">
                    <dl class="mb-0">
                        <dt>{{ $organization->church->name ?? '' }}</dt>
                        <dd>{{ $organization->church->street ?? '' }}<br>{{ $organization->church->city ?? '' }}, {{ $organization->church->state ?? '' }} {{ $organization->church->zip ?? '' }}</dd>
                        <dd>{{ $organization->church->website ?? '' }}</dd>
                        <dd>{{ $organization->church->pastor_name ?? '' }}</dd>
                    </dl>
                </div>
            </div>
            <div class="card mb-3">
                <h4 class="card-header">Contact</h4>
                <div class="card-block">
                    <dl class="mb-0">
                        <dt>{{ $organization->contact->name ?? '' }}</dt>
                        <dd>{{ $organization->contact->email ?? '' }}</dd>
                        <dd>{{ $organization->contact->phone ?? '' }}</dd>
                        <dd>{{ $organization->contact_desciption ?? '' }}</dd>
                    </dl>
                </div>
            </div>
            <div class="card mb-3">
                <h4 class="card-header">Student Pastor</h4>
                <div class="card-block">
                    <dl class="mb-0">
                        <dt>{{ $organization->studentPastor->name ?? '' }}</dt>
                        <dd>{{ $organization->studentPastor->email ?? '' }}</dd>
                        <dd>{{ $organization->studentPastor->phone ?? '' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <header class="card-header d-flex justify-content-between align-items-center">
                <h3>Auth Users</h3>
                <div class="sub header">
                    <a href="{{ action('Super\OrganizationUserController@create', $organization) }}" class="btn btn-secondary btn-sm">Add Auth User</a>
                </div>
            </header>
            <div class="card-block">
                @if ($organization->authUsers->count() > 0)
                    <table class="table table-striped">
                        @foreach ($organization->authUsers as $user)
                            <tr>
                                <td>{{ $user->person->name or '' }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @unless ($user->password)
                                        <input type="text" style="margin-bottom:0" readonly value="{{ route('complete.registration', [$user, $user->hash]) }}">
                                    @endunless
                                </td>
                                <td>
                                    @can ('impersonate', $user)
                                        <a href="{{ action('Auth\ImpersonationController@impersonate', $user) }}">impersonate</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
        <div class="card mb-3" id="notes">
            <header class="card-header">
                <h3>Notes <small class="text-muted">coming soon</small></h3>
            </header>
            <div class="card-block">
                @foreach ($organization->notes as $note)
                    <blockquote class="blockquote">
                        <p class="mb-0">
                            {!! nl2br($note->body) !!}
                            <footer class="blockquote-footer">{{ $note->author ? $note->author->email :'' }}, {{ $note->created_at->diffForHumans() }}</footer>
                        </p>
                    </div>
                @endforeach
                <form action="#" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea name="body" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <button class="btn btn-primary">Add Note</button>
                </form>
            </div>
        </div>
    </div>
@stop
