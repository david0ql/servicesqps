<?php

namespace App\Exports;

use App\Models\Service;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Response;

class CommunityReportExport
{
    protected $communityReports;

    public function __construct(array $communityReports)
    {
        $this->communityReports = $communityReports;
    }

    public function export()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheetIndex = 0;

            foreach ($this->communityReports as $communityName => $data) {
                $cleanedTitle = preg_replace('/[\\\\\/\\?\\*\\[\\]:]/', '', $communityName);
                $cleanedTitle = substr($cleanedTitle, 0, 31);

                if ($sheetIndex > 0) {
                    $spreadsheet->createSheet();
                }
                $spreadsheet->setActiveSheetIndex($sheetIndex);
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle($cleanedTitle);

                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Company Logo');
                $drawing->setPath(public_path('qps.png'));
                $drawing->setCoordinates('A1');
                $drawing->setHeight(100);
                $drawing->setWorksheet($sheet);

                $sheet->setCellValue('A8', '567 Meadow Bend Drive');
                $sheet->setCellValue('A9', 'DAVENPORT, FL 33837');
                $sheet->setCellValue('A10', 'Phone: (407)705-9647');
                $sheet->setCellValue('B9', 'INVOICE');
                $sheet->setCellValue('A11', 'info@qps1.net');
                $sheet->setCellValue('C4', 'VENDOR ID 1244442');
                $sheet->getStyle('C4')->getFont()->getColor()->setARGB('FF008000');

                $sheet->getStyle('A12:D12')->applyFromArray(['borders' => ['bottom' => ['borderStyle' => Border::BORDER_THIN]]]);

                $sheet->setCellValue('A13', 'Invoice To');
                $sheet->getStyle('A13')->getFont()->setBold(true);

                $sheet->setCellValue('A14', $communityName);
                $sheet->getStyle('A14')->getFont()->setBold(true);

                if ($communityName === 'Integra Heights At Olympus') {
                    $sheet->setCellValue('A15', '2100 Olympus Blvd, Clermont, FL 34714');
                } elseif ($communityName === 'Soleil Blue') {
                    $sheet->setCellValue('A15', '527 Neptune Bay Cir, St Cloud, FL 34769');
                }

                $sheet->setCellValue('D13', date('m/d/Y'));
                $sheet->getStyle('D13')->getFont()->setBold(true);

                $sheet->setCellValue('C13', 'DATE');
                $sheet->getStyle('C13')->getFont()->setBold(true);

                $sheet->setCellValue('C9', date('mdY') . '-' . $sheetIndex);
                $sheet->getStyle('C9')->getFont()->setBold(true);

                $this->populateSheetWithData($sheet, $data);
                $sheetIndex++;
            }

            $filePath = public_path("community_reports_generated.xlsx");
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            return Response::download($filePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    private function populateSheetWithData($sheet, $data)
    {
        $sheet->getStyle("A1:I100")->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);
        $sheet->getStyle("A1:I100")->applyFromArray(['fill' => ['fillType' => Fill::FILL_NONE]]);

        $headers = ['Date', 'Unit Number', 'Type', 'Service Value'];
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$column}17", $header);
            $column++;
        }

        $row = 18;
        foreach ($data as $rowData) {
            $sheet->setCellValue("A{$row}", \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($rowData['Date']));
            $sheet->getStyle("A{$row}")->getNumberFormat()->setFormatCode('MM/DD/YYYY');
            $sheet->setCellValue("B{$row}", $rowData['Unit Number']);
            $sheet->setCellValue("C{$row}", $rowData['Type']);
            $sheet->setCellValue("D{$row}", $rowData['Service Value']);
            $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');
            $row++;
        }

        // Añadir la fila de totales para las columnas numéricas
        $sheet->setCellValue("A{$row}", 'Total');
        $sheet->setCellValue("D{$row}", "=SUM(D18:D" . ($row - 1) . ")");
        $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('"$"#,##0.00');

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
            $sheet->getStyle($columnID)->getAlignment()->setWrapText(true);
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
        $sheet->getStyle("A17:D{$row}")->applyFromArray($styleArray);

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
        $sheet->getStyle("A17:D17")->applyFromArray($headerStyleArray);


    }
}
