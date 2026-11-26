<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blusa Seda Italiana</title>
  <link rel="preconnect" href="https://googleapis.com">
  <link rel="preconnect" href="https://gstatic.com" crossorigin>
  <link href="https://googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: #1a1a1a;
      background-color: #ffffff;
      -webkit-font-smoothing: antialiased;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 40px 24px;
    }

    .product-detail {
      display: grid;
      grid-template-columns: 1.05fr 1fr;
      gap: 64px;
      align-items: start;
    }

    /* Coluna da Imagem */
    .detail-image {
      width: 100%;
      background-color: #f4f3f1;
    }

    .detail-image img {
      width: 100%;
      height: auto;
      display: block;
      object-fit: cover;
    }

    /* Coluna de Informações */
    .detail-copy {
      padding-top: 8px;
      max-width: 480px;
    }

    .product-category {
      display: none; /* Oculto na captura visual, mas mantido no HTML */
    }

    .detail-copy h1 {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 2.6rem;
      font-weight: 400;
      color: #111111;
      line-height: 1.15;
      margin-bottom: 16px;
      letter-spacing: -0.01em;
    }

    .product-price {
      font-size: 1.05rem;
      font-weight: 400;
      color: #111111;
      margin-bottom: 24px;
    }

    .detail-description {
      font-size: 0.875rem;
      font-weight: 300;
      color: #7a7a7a;
      line-height: 1.6;
      margin-bottom: 32px;
    }

    /* Rótulos em caixa alta */
    .field-label {
      display: block;
      font-size: 0.65rem;
      font-weight: 600;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #333333;
      margin-bottom: 12px;
    }

    /* Botões de Tamanho */
    .size-options {
      display: flex;
      gap: 10px;
      margin-bottom: 32px;
    }

    .size-option {
      width: 40px;
      height: 40px;
      background-color: #ffffff;
      border: 1px solid #e5e5e5;
      font-family: 'Inter', sans-serif;
      font-size: 0.72rem;
      font-weight: 400;
      color: #555555;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: border-color 0.2s, color 0.2s;
    }

    .size-option:hover {
      border-color: #999999;
    }

    .size-option.selected {
      border: 1px solid #111111;
      color: #111111;
      font-weight: 500;
    }

    /* Linha de Quantidade + Botão Adicionar ao Carrinho */
    .purchase-row {
      display: flex;
      align-items: stretch;
      gap: 16px;
      margin-bottom: 24px;
    }

    .quantity-control {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: 1px solid #e5e5e5;
      height: 44px;
      width: 105px;
      padding: 0 8px;
      flex-shrink: 0;
    }

    .quantity-control button {
      background: none;
      border: none;
      font-size: 0.95rem;
      color: #555555;
      cursor: pointer;
      width: 24px;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .quantity-control button:hover {
      color: #111111;
    }

    .quantity-control output {
      font-size: 0.8rem;
      color: #111111;
      font-weight: 400;
    }

    .button-dark {
      flex: 1;
      height: 44px;
      background-color: #181818;
      color: #ffffff;
      border: none;
      font-family: 'Inter', sans-serif;
      font-size: 0.65rem;
      font-weight: 500;
      letter-spacing: 0.14em;
      text-transform: uppercase;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .button-dark:hover {
      background-color: #333333;
    }

    /* Aviso e link de retorno */
    .notice {
      font-size: 0.78rem;
      color: #7a7a7a;
      margin-bottom: 24px;
    }

    .notice a {
      color: #7a7a7a;
      text-decoration: none;
    }

    .notice a:hover {
      text-decoration: underline;
    }

    .text-link {
      display: inline-block;
      font-size: 0.65rem;
      font-weight: 500;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: #333333;
      text-decoration: none;
      border-bottom: 1px solid #333333;
      padding-bottom: 4px;
    }

    .text-link:hover {
      opacity: 0.7;
    }

    /* Responsividade para celular */
    @media (max-width: 768px) {
      .product-detail {
        grid-template-columns: 1fr;
        gap: 32px;
      }

      .detail-copy h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <main id="app-main">
    <section class="container product-detail" id="product-detail">
      <div class="detail-image">
        <img src="../produtos/uploads/6b043da237e8d.jpg" alt="Blusa Seda Italiana">
      </div>
      <div class="detail-copy">
        <span class="product-category">Feminino</span>
        <h1>Blusa Seda Italiana</h1>
        <p class="product-price">R$&nbsp;890,00</p>
        <p class="detail-description">Seda de toque suave com caimento leve e acabamento refinado.</p>
        
        <span class="field-label">Selecione o tamanho</span>
        <div class="size-options" id="size-options">
          <button type="button" class="size-option" data-size="PP" aria-pressed="false">PP</button>
          <button type="button" class="size-option" data-size="P" aria-pressed="false">P</button>
          <button type="button" class="size-option" data-size="M" aria-pressed="false">M</button>
          <button type="button" class="size-option selected" data-size="G" aria-pressed="true">G</button>
        </div>

        <span class="field-label">Quantidade</span>
        <div class="purchase-row">
          <div class="quantity-control">
            <button type="button" id="quantity-minus" aria-label="Diminuir">-</button>
            <output id="quantity-value">2</output>
            <button type="button" id="quantity-plus" aria-label="Aumentar">+</button>
          </div>
          <button class="button button-dark" id="add-to-cart" type="button">Adicionar ao carrinho</button>
        </div>

        <p class="notice" id="product-notice" aria-live="polite">
          Produto adicionado. Ver carrinho · 
          <a href="#/carrinho">Abrir carrinho</a>
        </p>
        <a class="text-link" href="#/categoria/feminino">Continuar comprando</a>
      </div>
    </section>
  </main>
</body>
</html>
