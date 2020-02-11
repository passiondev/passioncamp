@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="d-lg-flex justify-content-between align-items-center mb-lg-2">
            <h1 class="mb-2 mb-lg-0">Notifications</h1>
        </header>

        <div class="card mt-4">
            <h3 class="card-header">New Notification</h3>
            <div class="card-block">
                <form action="{{ action('OrganizationNotificationController@store') }}" method="POST" onsubmit="return confirm('This will send a notification to the selected church(es). Are you sure?')">
                    @csrf

                    <div class="form-group">
                        <label for="organization">Church</label>
                        <select name="organization" class="form-control mb-2 mr-sm-2 mb-sm-0">
                            <option selected value="">- All -</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}" @if (request('organization') == $organization->id) selected @endif>
                                    {{ $organization->church->name }} - {{ $organization->church->location }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject">Message</label>
                        <input type="text" class="form-control" name="subject" id="subject" required>
                    </div>


                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <div class="card mt-4 mb-2" id="sent">
            <h3 class="card-header">Sent Notifications</h3>
            <div>
                <table class="table table-responsive table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Church</th>
                            <th>Message</th>
                            <th>Sent At</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach ($notifications as $notification)
                        <tr>
                            <td>
                                {{ $notification->notifiable->church->name }}
                            </td>
                            <td>
                                <small>{{ Str::limit($notification->data['subject'], 20) }}</small>
                            </td>
                            <td>
                                {{ $notification->created_at }}
                            </td>
                            <td>
                                @if($notification->unread())
                                    <span class="badge badge-success">new</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ action('OrganizationNotificationController@destroy', $notification) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link">delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        {{ $notifications->fragment('sent')->links() }}
    </div>
@stop
