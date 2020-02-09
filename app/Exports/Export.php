<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Writer;

abstract class Export
{
    public function __toString()
    {
        return json_encode($this->data());
    }

    abstract public function data();

    public function toCsv()
    {
        $data = $this->collapseWithPrefixedKeys($this->data());

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(array_keys($data[0]));
        $csv->insertAll($data);

        return $csv;
    }

    public function filename()
    {
        return Str::snake(class_basename($this)).'-'.time().'.csv';
    }

    public function store()
    {
        Storage::disk('exports')->put($this->filename(), $this->toCsv());

        return $this->filename();
    }

    public function download()
    {
        return $this->toCsv()->output($this->filename());
    }

    protected function collapseWithPrefixedKeys($data)
    {
        return collect($data)->map(function ($ticket) {
            return collect($ticket)->mapWithKeys(function ($value, $key) {
                if (\is_array($value)) {
                    return collect($value)->mapWithKeys(function ($v, $k) use ($key) {
                        return [$key.' '.$k => $v];
                    });
                }

                return [$key => $value];
            })->all();
        });
    }
}
