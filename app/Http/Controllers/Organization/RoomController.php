<?php

namespace App\Http\Controllers\Organization;

use App\Organization;
use App\Http\Requests;
use App\PrintJobHandler;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    public function printAll(Request $request, Organization $organization)
    {
        $pdf = new \HTML2PDF('P', [50.8,58.7], 'en', true, 'UTF-8', 0);

        $content = $organization->rooms->map(function ($room) {
            return view('roominglist/partials/label', compact('room'))->render();
        });

        $pdf->writeHTML($content->implode(''));

        $print_handler = (new PrintJobHandler)
            ->withPrinter($request->session()->get('printer'))
            ->setTitle($organization->church->name)
            ->output($pdf);

        if ($request->ajax() || $request->wantsJson()) {
            return response('<i class="checkmark green icon"></i>', 201);
        } else {
            return redirect()->back()->withSuccess('Printing job queued.');
        }
    }
}
