@if (App::environment('production'))
    <script src="//d2wy8f7a9ursnm.cloudfront.net/v4/bugsnag.min.js"></script>
    <script>window.bugsnagClient = bugsnag({!! json_encode(config('bugsnag.api_key')) !!})</script>
@endif
