<?php

namespace App\Services\Printing;

use PrintNode;
use Illuminate\Support\Facades\Cache;

class PrintNodePrinter extends Printer
{
    protected $client;
    private $key;

    public function __construct(PrintNode\Client $client, $key)
    {
        $this->client = $client;
        $this->key = $key;
    }

    public function client()
    {
        return $this->client;
    }

    public function print($printer, $url, array $payload)
    {
        $job = new PrintNode\Entity\PrintJob($this->client());
        $job->printer = $printer;
        $job->contentType = 'pdf_uri';
        $job->content = $url;

        $this->mapJobPayload($job, $payload);

        return $this->client()->createPrintJob($job);
    }

    public function jobs($printer, $max = 500, $offset = 0)
    {
        return $this->client()->viewPrintJobs($offset, $max, null, $printer);
    }

    public function printers()
    {
        return Cache::remember($this->cacheKey(), 10, function () {
            return $this->client()->viewPrinters();
        });
    }

    public function refreshPrinters()
    {
        Cache::forget($this->cacheKey());
    }

    protected function cacheKey()
    {
        return 'printers::' . $this->key;
    }

    private function mapJobPayload($job, $payload)
    {
        collect($payload)->filter(function ($value, $property)  {
            return property_exists(PrintNode\Entity\PrintJob::class, $property);
        })->each(function ($value, $property) use (&$job) {
            $job->{$property} = $value;
        });
    }
}
