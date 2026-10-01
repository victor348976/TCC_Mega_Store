const produtos = [];

function formatarPreco(valor) {
  return valor.toLocaleString("pt-BR", {
    style: "currency",
    currency: "BRL",
  });
}

function obterProduto(id) {
  return produtos.find((produto) => produto.id === Number(id));
}
