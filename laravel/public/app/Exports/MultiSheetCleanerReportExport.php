<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Response;

class MultiSheetCleanerReportExport
{
    protected $cleanerReports;

    public function __construct(array $cleanerReports)
    {
        $this->cleanerReports = $cleanerReports;
    }

    public function export()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheetIndex = 0;

            foreach ($this->cleanerReports as $cleanerName => $data) {
                if ($sheetIndex > 0) {
                    $spreadsheet->createSheet();
                }
                $spreadsheet->setActiveSheetIndex($sheetIndex);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle($cleanerName);

                // Inserta la imagen en la celda A1
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Company Logo');
                $drawing->setPath(public_path('qps.png'));
                $drawing->setCoordinates('A1');
                $drawing->setHeight(100);
                $drawing->setWorksheet($sheet);

                // Inserta el texto en la columna A, comenzando desde A8
                $sheet->setCellValue('A8', '567 Meadow Bend Drive');
                $sheet->setCellValue('A9', 'DAVENPORT, FL 33837');
                $sheet->setCellValue('A10', 'Phone:   (407)705-9647');
                $sheet->setCellValue('A11', 'info@qps1.net');

                $this->populateSheetWithData($sheet, $data);
                $sheetIndex++;
            }

            $filePath = public_path("cleaner_reports_generated.xlsx");
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            return Response::download($filePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    protected function populateSheetWithData($sheet, $data)
    {
        $sheet->getStyle("A1:I100")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);
        $sheet->getStyle("A1:I100")->applyFromArray(['fill' => ['fillType' => Fill::FILL_NONE]]);

        $headers = ['Date', 'Community', 'Unit Number', 'Type', 'Commission', 'Extras', 'Total'];
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$column}14", $header);
            $column++;
        }

        $row = 15;
        foreach ($data as $rowData) {
            $sheet->setCellValue("A{$row}", \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($rowData['Date']));
            $sheet->getStyle("A{$row}")->getNumberFormat()->setFormatCode('MM/DD/YYYY');

            $sheet->setCellValue("B{$row}", $rowData['Community']);
            $sheet->setCellValue("C{$row}", $rowData['Unit Number']);
            $sheet->setCellValue("D{$row}", $rowData['Type']);

            // Asignar formato de moneda para las comisiones y el total
            $sheet->setCellValue("E{$row}", (float)$rowData['Service Commission']);
            $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');

            $sheet->setCellValue("F{$row}", (float)$rowData['Extra Commission']);
            $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');

            $sheet->setCellValue("G{$row}", (float)$rowData['Total Cleaner']);
            $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');

            $row++;
        }

        // Añadir la fila de totales para las columnas numéricas
        $sheet->setCellValue("D{$row}", "Total:");
        $sheet->setCellValue("E{$row}", "=SUM(E15:E" . ($row - 1) . ")");
        $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00'); // Formato de moneda en el total

        $sheet->setCellValue("F{$row}", "=SUM(F15:F" . ($row - 1) . ")");
        $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00'); // Formato de moneda en el total

        $sheet->setCellValue("G{$row}", "=SUM(G15:G" . ($row - 1) . ")");
        $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00'); // Formato de moneda en el total

        // Ajustes de estilo
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(12); // Define un ancho fijo (ajusta el valor según lo que necesites)
            $sheet->getStyle($columnID)->getAlignment()->setWrapText(true); // Activa el ajuste de texto para que pase a la siguiente línea
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle("A14:G{$row}")->applyFromArray($styleArray);

        $headerStyleArray = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFCCCCCC'],
            ],
        ];
        $sheet->getStyle("A14:G14")->applyFromArray($headerStyleArray);
    }
}