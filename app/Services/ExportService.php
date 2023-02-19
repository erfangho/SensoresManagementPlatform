<?php

namespace App\Services;


use Laracsv\Export;
use Morilog\Jalali\Jalalian;

class ExportService
{
    public function exportCsv($model, $fileName, $parametersArray)
    {
        foreach ($model as $field) {
            $field['created_at'] = Jalalian::fromDateTime($field['created_at'])->format('Y/m/d H:i:s');
        }

        $csvExporter = new Export();

        $csvExporter->build($model, $parametersArray);

        return $csvExporter->download($fileName);
    }
}
