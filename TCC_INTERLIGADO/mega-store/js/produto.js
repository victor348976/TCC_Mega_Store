function getSelectedSize(sizeOptions) {
    const selectedOption = sizeOptions.querySelector('.selected');
    return selectedOption ? selectedOption.dataset.size : '';
}

function updateQuantity(output, quantity) {
    output.value = quantity;
    output.textContent = quantity;
}

function addProductToCart(product, size, quantity) {
    let cart;

    try {
        cart = JSON.parse(localStorage.getItem('mega-carrinho') || '[]');
    } catch {
        cart = [];
    }

    const existingItem = cart.find((item) => {
        return item.id === product.id && item.tamanho === size;
    });

    if (existingItem) {
        existingItem.quantidade += quantity;
    } else {
        cart.push({ id: product.id, tamanho: size, quantidade: quantity });
    }

    localStorage.setItem('mega-carrinho', JSON.stringify(cart));
    updateCartCount();
}

function renderProductDetails(product) {
    document.title = `${product.nome} | Mega Store`;
    document.querySelector('#detail-image').src = product.imagem;
    document.querySelector('#detail-image').alt = product.nome;
    document.querySelector('#detail-category').textContent = product.categoria;
    document.querySelector('#detail-name').textContent = product.nome;
    document.querySelector('#detail-price').textContent = formatarPreco(product.preco);
    document.querySelector('#detail-description').textContent = product.descricao;

    const sizeOptions = document.querySelector('#size-options');
    sizeOptions.innerHTML = product.tamanhos.map((size, index) => `
        <button
            type="button"
            class="size-option${index === 0 ? ' selected' : ''}"
            aria-pressed="${index === 0}"
            data-size="${size}"
        >${size}</button>
    `).join('');

    sizeOptions.addEventListener('click', (event) => {
        const option = event.target.closest('[data-size]');
        if (!option) return;

        sizeOptions.querySelectorAll('.size-option').forEach((button) => {
            const isSelected = button === option;
            button.classList.toggle('selected', isSelected);
            button.setAttribute('aria-pressed', String(isSelected));
        });
    });

    const quantityOutput = document.querySelector('#quantity-value');
    let quantity = 1;

    document.querySelector('#quantity-minus').addEventListener('click', () => {
        quantity = Math.max(1, quantity - 1);
        updateQuantity(quantityOutput, quantity);
    });

    document.querySelector('#quantity-plus').addEventListener('click', () => {
        quantity += 1;
        updateQuantity(quantityOutput, quantity);
    });

    document.querySelector('#add-to-cart').addEventListener('click', () => {
        addProductToCart(product, getSelectedSize(sizeOptions), quantity);
        document.querySelector('#product-notice').textContent = 'Adicionado ao carrinho.';
    });
}

function showMissingProduct() {
    document.querySelector('#product-detail').innerHTML = `
        <div class="empty-state">
            <h2>Produto não encontrado</h2>
            <a class="button button-dark" href="index.html">Voltar à coleção</a>
        </div>
    `;
}

function initializeProductPage() {
    const productId = new URLSearchParams(window.location.search).get('id');
    const product = obterProduto(productId);

    if (!product) {
        showMissingProduct();
        return;
    }

    renderProductDetails(product);
}

document.addEventListener('DOMContentLoaded', initializeProductPage);
