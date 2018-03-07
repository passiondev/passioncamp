<p>We are excited your {{ str_plural('student', $order->num_tickets) }} {{ $order->num_tickets == 1 ? 'is' : 'are' }} planning to join PCC Students at Passion Camp 2018! We are looking forward to all that Jesus is going to do in students' lives during our time together in Daytona Beach!</p>

<p>If you have any questions, feel free to email our team at students@passioncitychurch.com.</p>

<p>We look forward to seeing everyone in Daytona!<br />
The Passion Students Team</p>

<hr>

@include('order.receipt')
