@include ('errors.validation')

@if (auth()->user()->isSuperAdmin())
    <div class="form-group">
        <label>Church</label>
        <p class="form-control-static font-weight-bold">{{ $organization->church->name or 'PASSION CAMP ADMIN' }}</p>
    </div>
@endif

<div class="form-group">
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->person->first_name ?? null) }}" class="form-control">
</div>

<div class="form-group">
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->person->last_name ?? null) }}" class="form-control">
</div>

<div class="form-group">
    <label for="email">Email Address</label>
    <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? null) }}" class="form-control">
</div>

