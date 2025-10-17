<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Produtos - PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        header { display: flex; align-items: center; margin-bottom: 20px; }
        header img { height: 50px; margin-right: 15px; }
        header h2 { font-size: 18px; margin: 0; }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; }

        /* Cores de stock */
        .stock-high { background-color: #d1fae5; color: #065f46; font-weight: bold; text-align: center; }
        .stock-medium { background-color: #fef3c7; color: #78350f; font-weight: bold; text-align: center; }
        .stock-low { background-color: #fee2e2; color: #991b1b; font-weight: bold; text-align: center; }
    </style>
</head>
<body>
    <header>
        <h2>Lista de Produtos</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Categoria</th>
                <th>Marca</th>
                <th>Preço (€)</th>
                <th>Stock</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category_name }}</td>
                    <td>{{ $product->brand }}</td>
                    <td>{{ number_format($product->price, 2, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td class="@if($product->stock > 10) stock-high
                               @elseif($product->stock >=5 && $product->stock <=10) stock-medium
                               @else stock-low @endif">
                        @if($product->stock > 10)
                            Em Stock
                        @elseif($product->stock >=5 && $product->stock <=10)
                            Stock Médio
                        @else
                            Pouco Stock
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
