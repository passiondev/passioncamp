<p>We are excited your {{ str_plural('student', $order->num_tickets) }} {{ $order->num_tickets == 1 ? 'is' : 'are' }} planning to join PCC Students at Passion Camp 2018! We are looking forward to all that Jesus is going to do in students' lives during our time together in Daytona Beach!</p>

<p>If you have any questions, please contact our team at <a href="mailto:students@passioncitychurch.com">students@passioncitychurch.com</a>.</p>

<p>We look forward to seeing everyone in Daytona!<br />
<strong>The Passion Students Team</strong></p>

<p style="border-top:solid 1px #B8C2CC;font-size:1;margin:20px auto;width:100%;"></p>

@include('order.receipt')

@if ($order->user->balance)
    <div style="margin:1.5rem auto; background-color: #F8FAFC; padding: 1rem; text-align:center">
        @if ($order->user->isRegistered())
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;"><tr><td align="center" bgcolor="#6574CD" role="presentation" style="border:none;border-radius:3px;color:#ffffff;cursor:auto;padding:10px 25px;" valign="middle"><a href="{{ route('login') }}" style="background: #6574CD; font-family: system-ui, Helvetica Neue, Arial, Helvetica, sans-serif; font-size: 16px; font-weight: bold; line-height: 120%; Margin: 0; text-transform: none; text-decoration: none; color: inherit;" target="_blank">Sign In</a></td></tr></table>
        @else
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;"><tr><td align="center" bgcolor="#6574CD" role="presentation" style="border:none;border-radius:3px;color:#ffffff;cursor:auto;padding:10px 25px;" valign="middle"><a href="{{ url()->signedRoute('auth.register.create', $order->user) }}" style="background: #6574CD; font-family: system-ui, Helvetica Neue, Arial, Helvetica, sans-serif; font-size: 16px; font-weight: bold; line-height: 120%; Margin: 0; text-transform: none; text-decoration: none; color: inherit;" target="_blank">Create Your Account</a></td></tr></table>
        @endif

        <p style="font-size:14px; font-style: italic; color: #8795A1; margin-bottom: 0">
            @if ($order->user->isRegistered())
                Sign in to your Passion Camp account to pay your remaining balance.
            @else
                Create your Passion Camp account to pay your remaining balance.
            @endif
        </p>
    </div>
@endif
