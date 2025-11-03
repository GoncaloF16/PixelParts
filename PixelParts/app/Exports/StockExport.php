<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
            'Valor em Stock (€)'
        ];
    }

    public function map($product): array
    {
        $valorStock = $product->price * $product->stock;

        return [
            $product->name,
            $product->category_name,
            $product->brand,
            $product->price,
            $product->stock,
            $valorStock
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->products) + 1;
        $totalRow = $lastRow + 2;

        // Formatar preços como moeda
        $sheet->getStyle('D2:D' . $lastRow)->getNumberFormat()
            ->setFormatCode('€#,##0.00');

        $sheet->getStyle('F2:F' . $lastRow)->getNumberFormat()
            ->setFormatCode('€#,##0.00');

        // Adicionar linha de total
        $sheet->setCellValue('E' . $totalRow, 'Valor Total em Stock:');
        $sheet->setCellValue('F' . $totalRow, '=SUM(F2:F' . $lastRow . ')');

        // Formatar célula de total
        $sheet->getStyle('F' . $totalRow)->getNumberFormat()
            ->setFormatCode('€#,##0.00');

        // Negrito nos cabeçalhos
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Negrito na linha de total
        $sheet->getStyle('E' . $totalRow . ':F' . $totalRow)->getFont()->setBold(true);

        // Ajustar largura das colunas
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(20);

        return [
            1 => ['font' => ['bold' => true]],
            $totalRow => ['font' => ['bold' => true]]
        ];
    }
}
