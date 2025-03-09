<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Collection;

class MultiSheetCleanerReportExport
{
    protected $cleanerReports;

    public function __construct(array $cleanerReports)
    {
        $this->cleanerReports = $cleanerReports;
    }

    public function export()
    {

        // Cargar el formato existente
        $spreadsheet = IOFactory::load(public_path('format.xlsx'));

        // Inicializar el índice de hoja
        $sheetIndex = 0;

        foreach ($this->cleanerReports as $cleanerName => $data) {
            // Crear nueva hoja
            if ($sheetIndex > 0) {
                $spreadsheet->createSheet();
            }
            $spreadsheet->setActiveSheetIndex($sheetIndex);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle($cleanerName);

            // Escribir encabezados en A14
            $headers = [
                'Community',
                'Unit Number',
                'Type',
                'Service Commission',
                'Extra Commission',
                'Total Cleaner',
            ];
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue("{$column}14", $header);
                $column++;
            }

            // Escribir datos a partir de A15
            $row = 15;
            foreach ($data as $rowData) {
                $sheet->setCellValue("A{$row}", $rowData['Community']);
                $sheet->setCellValue("B{$row}", $rowData['Unit Number']);
                $sheet->setCellValue("C{$row}", $rowData['Type']);
                $sheet->setCellValue("D{$row}", $rowData['Service Commission']);
                $sheet->setCellValue("E{$row}", $rowData['Extra Commission']);
                $sheet->setCellValue("F{$row}", $rowData['Total Cleaner']);
                $row++;
            }

            // Incrementar el índice de hoja
            $sheetIndex++;
        }

        // Guardar el archivo en formato .xlsx
        $writer = new Xlsx($spreadsheet);
        $filePath = public_path('cleaner_reports.xlsx');
        $writer->save($filePath);

        return $filePath;
    }
}
