<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Facades\Response;

class ServiceReportExport
{
    protected $generalReports;
    protected $costs;

    public function __construct(array $generalReports, array $costs)
    {
        $this->generalReports = $generalReports;
        $this->costs = $costs;
    }

    public function export()
    {
        try {
            $spreadsheet = new Spreadsheet();

            // Crear y configurar la hoja de cálculo
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Service Report');

            // Añadir el logo
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Company Logo');
            $drawing->setPath(public_path('qps.png'));
            $drawing->setCoordinates('A1');
            $drawing->setHeight(100);
            $drawing->setWorksheet($sheet);

            // Insertar detalles de contacto
            $sheet->setCellValue('A8', '567 Meadow Bend Drive');
            $sheet->setCellValue('A9', 'DAVENPORT, FL 33837');
            $sheet->setCellValue('A10', 'Phone:   (407)705-9647');
            $sheet->setCellValue('A11', 'info@qps1.net');

            // Configurar encabezados
            $headers = [
                'Date', 'Community', 'Type', 'Unit Number', 'Service Price', 'Service Commission',
                'Extras Price', 'Extras Commission', 'Total Cleaner', 'Hugo', 'Felix', 'Felix Hijo',
                'Cleaner', 'Status'
            ];

            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue("{$column}14", $header);
                $column++;
            }

            // Agregar datos a las filas
            $row = 15;
            foreach ($this->generalReports as $key => $rowData) {
                $sheet->setCellValue("A{$row}", \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($rowData['Date'] ?? ''));
                $sheet->getStyle("A{$row}")->getNumberFormat()->setFormatCode('MM/DD/YYYY');
                $sheet->setCellValue("B{$row}", $rowData['Community'] ?? '');
                $sheet->setCellValue("C{$row}", $rowData['Type'] ?? '');
                $sheet->setCellValue("D{$row}", $rowData['Unit Number'] ?? '');
                $sheet->setCellValue("E{$row}", $rowData['Service Price'] ?? 0);
                $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->setCellValue("F{$row}", $rowData['Service Commission'] ?? 0);
                $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->setCellValue("G{$row}", $rowData['Extras Price'] ?? 0);
                $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->setCellValue("H{$row}", $rowData['Extras Commission'] ?? 0);
                $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->setCellValue("I{$row}", $rowData['Total Cleaner'] ?? 0);
                $sheet->getStyle("I{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->setCellValue("J{$row}", $rowData['Partner'] ?? 0);
                $sheet->getStyle("J{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->setCellValue("K{$row}", $rowData['Total'] ?? 0);
                $sheet->getStyle("K{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->setCellValue("L{$row}", $rowData['Felix_Hijo'] ?? 0);
                $sheet->getStyle("L{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $sheet->setCellValue("M{$row}", $rowData['Cleaner'] ?? '');
                $sheet->setCellValue("N{$row}", $rowData['Status'] ?? '');
                $row++;
            }

            // Calcular totales de servicios
            $sheet->setCellValue("D{$row}", "Total:");
            foreach (range('E', 'L') as $col) {
                $sheet->setCellValue("{$col}{$row}", "=SUM({$col}15:{$col}" . ($row - 1) . ")");
                $sheet->getStyle("{$col}{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
            }

            // Guardar totales en variables
            $total_hugo = $sheet->getCell("J{$row}")->getCalculatedValue();
            $total_felix = $sheet->getCell("K{$row}")->getCalculatedValue();
            $total_felix_hijo = $sheet->getCell("L{$row}")->getCalculatedValue();

            // Agregar tabla de costos
            $row += 2;
            $sheet->setCellValue("A{$row}", 'Costs');
            $row++;
            $sheet->setCellValue("A{$row}", 'Date');
            $sheet->setCellValue("B{$row}", 'Description');
            $sheet->setCellValue("C{$row}", 'Amount');
            $row++;

            foreach ($this->costs as $cost) {
                $sheet->setCellValue("A{$row}", \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($cost['date'] ?? ''));
                $sheet->getStyle("A{$row}")->getNumberFormat()->setFormatCode('MM/DD/YYYY');
                $sheet->setCellValue("B{$row}", $cost['description'] ?? '');
                $sheet->setCellValue("C{$row}", $cost['amount'] ?? 0);
                $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
                $row++;
            }

            $sheet->setCellValue("B{$row}", 'Total:');
            $sheet->setCellValue("C{$row}", "=SUM(C16:C" . ($row - 1) . ")");
            $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
            $total_costs = $sheet->getCell("C{$row}")->getCalculatedValue();

            // Agregar tabla de ganancias (profits)
            $row += 3;
            $sheet->setCellValue("A{$row}", 'Profits');
            $row++;
            $sheet->setCellValue("A{$row}", 'Partner');
            $sheet->setCellValue("B{$row}", 'Subtotal');
            $sheet->setCellValue("C{$row}", 'Costs');
            $sheet->setCellValue("D{$row}", 'Total');
            $row++;
            $sheet->setCellValue("A{$row}", 'Hugo');
            $sheet->setCellValue("B{$row}", $total_hugo);
            $sheet->setCellValue("C{$row}", $total_costs * 0.25);
            $sheet->setCellValue("D{$row}", "=B{$row}-C{$row}");

            $row++;
            $sheet->setCellValue("A{$row}", 'Felix');
            $sheet->setCellValue("B{$row}", $total_felix);
            $sheet->setCellValue("C{$row}", $total_costs * 0.5);
            $sheet->setCellValue("D{$row}", "=B{$row}-C{$row}");

            $row++;
            $sheet->setCellValue("A{$row}", 'Felix Hijo');
            $sheet->setCellValue("B{$row}", $total_felix_hijo);
            $sheet->setCellValue("C{$row}", $total_costs * 0.25);
            $sheet->setCellValue("D{$row}", "=B{$row}-C{$row}");

            // Ajustar dimensiones y estilos
            foreach (range('A', 'N') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
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
            $sheet->getStyle("A14:N{$row}")->applyFromArray($styleArray);

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
            $sheet->getStyle("A14:N14")->applyFromArray($headerStyleArray);

            // Guardar archivo
            $filePath = public_path("general_report.xlsx");
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            return response()->download($filePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
