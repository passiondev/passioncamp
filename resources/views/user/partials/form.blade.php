@include ('errors.validation')

@if (auth()->user()->isSuperAdmin())
    <div class="form-group">
        <label for="organization">Organization</label>
        <select name="organization" id="organization" class="form-control">
            <option value="ADMIN" @if (old('organization', $user->isSuperAdmin() ? 'ADMIN' : null) == 'ADMIN') selected @endif>PASSION CAMP ADMIN</option>
            @foreach ($organizationOptions as $key => $value)
                <option value="{{ $key }}" @if (old('organization', $user->organization ? $user->organization->id : null) == $key) selected @endif>{{ $value }}</option>
            @endforeach
        </select>
    </div>
@endif

<div class="form-group">
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->person ? $user->person->first_name : null) }}" class="form-control">
</div>

<div class="form-group">
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->person ? $user->person->last_name : null) }}" class="form-control">
</div>

<div class="form-group">
    <label for="email">Email Address</label>
    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control">
</div>

