<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use PrintNode\Client as PrintNode;

interface PrinterIndexComposer
{
    public function __construct(PrintNode $printnode);

    public function compose(View $view);
}
