<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use PrintNode\Client as PrintNode;
use Illuminate\Support\Facades\Session;

interface PrinterIndexComposerContract
{
    public function __construct(PrintNode $printnode);

    public function compose(View $view);
}
