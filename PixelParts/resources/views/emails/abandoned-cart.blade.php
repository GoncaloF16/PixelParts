<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho Abandonado</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #10B981 0%, #3B82F6 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .product-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: #f9fafb;
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }
        .product-info {
            flex: 1;
        }
        .product-name {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .product-details {
            font-size: 14px;
            color: #6b7280;
        }
        .product-price {
            font-size: 18px;
            font-weight: bold;
            color: #10B981;
            text-align: right;
        }
        .total-section {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            border: 2px solid #10B981;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-label {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #10B981;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #10B981 0%, #3B82F6 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        }
        .cta-button:hover {
            opacity: 0.9;
        }
        .footer {
            background-color: #1f2937;
            color: #9ca3af;
            padding: 30px 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #10B981;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>O Seu Carrinho Aguarda Finalização</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Caro(a) {{ $user->name }},
            </div>

            <div class="message">
                Verificámos que tem produtos no seu carrinho de compras que ainda não foram finalizados.
                Guardámos os seus artigos para que possa concluir a sua encomenda quando for conveniente.
            </div>

            <!-- Products List -->
            <div style="margin: 30px 0;">
                @foreach($cartItems as $item)
                    <div class="product-item">
                        <div style="width: 80px; height: 80px; background-color: #e5e7eb; border-radius: 8px; margin-right: 15px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </div>
                        <div class="product-info">
                            <div class="product-name">{{ $item['name'] }}</div>
                            <div class="product-details">
                                Quantidade: {{ $item['quantity'] }} ×
                                €{{ number_format($item['price'], 2, ',', '.') }}
                            </div>
                        </div>
                        <div class="product-price">
                            €{{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total -->
            <div class="total-section">
                <div class="total-row">
                    <span class="total-label">Total do Carrinho:</span>
                    <span class="total-amount">€{{ number_format($totalAmount, 2, ',', '.') }}</span>
                </div>
            </div>

            <!-- CTA Button -->
            <div style="text-align: center;">
                <a href="{{ $recoveryUrl }}" class="cta-button">
                    Concluir Encomenda
                </a>
            </div>

            <div class="divider"></div>

            <div class="message" style="font-size: 14px; color: #6b7280;">
                <strong>Precisa de Assistência?</strong><br>
                A nossa equipa de apoio ao cliente está disponível para ajudar.
                Contacte-nos através do email suporte@pixelparts.com ou utilize o chat disponível no website.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>PixelParts</strong> - Componentes Gaming de Qualidade<br>
                <a href="{{ config('app.url') }}">www.pixelparts.com</a>
            </p>
            <p style="margin-top: 15px; font-size: 12px;">
                Recebeu este email porque tem produtos pendentes no seu carrinho de compras.<br>
                Para deixar de receber notificações, pode <a href="#">gerir as suas preferências</a>.
            </p>
        </div>
    </div>
</body>
</html>
