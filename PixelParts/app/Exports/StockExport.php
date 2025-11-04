<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StockExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Categoria',
            'Marca',
            'Preço (€)',
            'Stock',
            'Estado',
            'Valor em Stock (€)'
        ];
    }

    public function map($product): array
    {
        $valorStock = $product->price * $product->stock;

        // Determinar estado do stock
        if ($product->stock > 10) {
            $estado = 'Em Stock';
        } elseif ($product->stock >= 5 && $product->stock <= 10) {
            $estado = 'Stock Médio';
        } else {
            $estado = 'Pouco Stock';
        }

        return [
            $product->name,
            $product->category_name,
            $product->brand,
            $product->price,
            $product->stock,
            $estado,
            $valorStock
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->products) + 1;
        $totalRow = $lastRow + 2;

        // ===== CABEÇALHO =====
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2563EB'] // Azul
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // ===== FORMATAÇÃO DAS LINHAS DE DADOS =====
        for ($row = 2; $row <= $lastRow; $row++) {
            // Cor alternada nas linhas
            $fillColor = ($row % 2 == 0) ? 'F3F4F6' : 'FFFFFF';

            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $fillColor]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D1D5DB']
                    ]
                ]
            ]);

            // Alinhar texto
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("B{$row}:C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}:G{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Colorir coluna de Estado baseado no valor
            $estadoValue = $sheet->getCell("F{$row}")->getValue();

            if ($estadoValue === 'Em Stock') {
                $sheet->getStyle("F{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5'] // Verde claro
                    ],
                    'font' => [
                        'color' => ['rgb' => '065F46'], // Verde escuro
                        'bold' => true
                    ]
                ]);
            } elseif ($estadoValue === 'Stock Médio') {
                $sheet->getStyle("F{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7'] // Amarelo claro
                    ],
                    'font' => [
                        'color' => ['rgb' => '92400E'], // Amarelo escuro
                        'bold' => true
                    ]
                ]);
            } else {
                $sheet->getStyle("F{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEE2E2'] // Vermelho claro
                    ],
                    'font' => [
                        'color' => ['rgb' => '991B1B'], // Vermelho escuro
                        'bold' => true
                    ]
                ]);
            }
        }

        // ===== FORMATAÇÃO DE PREÇOS =====
        $sheet->getStyle('D2:D' . $lastRow)->getNumberFormat()
            ->setFormatCode('€#,##0.00');
        $sheet->getStyle('G2:G' . $lastRow)->getNumberFormat()
            ->setFormatCode('€#,##0.00');

        // ===== LINHA DE TOTAL =====
        $sheet->setCellValue('F' . $totalRow, 'VALOR TOTAL EM STOCK:');
        $sheet->setCellValue('G' . $totalRow, '=SUM(G2:G' . $lastRow . ')');

        $sheet->getStyle("F{$totalRow}:G{$totalRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'] // Verde
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        $sheet->getStyle('G' . $totalRow)->getNumberFormat()
            ->setFormatCode('€#,##0.00');

        // ===== LARGURA DAS COLUNAS =====
        $sheet->getColumnDimension('A')->setWidth(45);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(28);
        $sheet->getColumnDimension('G')->setWidth(20);

        // ===== ALTURA DAS LINHAS =====
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension($totalRow)->setRowHeight(25);

        return [];
    }
}
