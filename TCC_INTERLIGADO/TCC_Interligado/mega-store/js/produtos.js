// Catálogo de demonstração para a interface unificada. Substitua pelo catálogo
// da API quando o backend de produtos estiver disponível.
const produtos = [
  { id: 1, nome: "Vestido Noir Éternel", categoria: "Feminino", preco: 12890, imagem: "../home/img/product-dress.png", descricao: "Vestido de linhas elegantes, pensado para ocasiões especiais e feito para atravessar estações.", tamanhos: ["PP", "P", "M", "G"] },
  { id: 2, nome: "Blusa Seda Italiana", categoria: "Feminino", preco: 890, imagem: "../home/img/product-silk.png", descricao: "Seda de toque suave com caimento leve e acabamento refinado.", tamanhos: ["PP", "P", "M", "G"] },
  { id: 3, nome: "Trench Coat Ivoire", categoria: "Feminino", preco: 8750, imagem: "../home/img/ready-to-wear.png", descricao: "Uma peça atemporal com silhueta contemporânea e detalhes cuidadosamente finalizados.", tamanhos: ["P", "M", "G"] },
  { id: 4, nome: "Saia Alfaiataria Essencial", categoria: "Feminino", preco: 1190, imagem: "../home/img/product-dress.png", descricao: "Alfaiataria versátil com estrutura confortável para acompanhar diferentes momentos.", tamanhos: ["PP", "P", "M", "G"] },
  { id: 5, nome: "Blazer Grafite Tailored", categoria: "Masculino", preco: 4590, imagem: "../home/img/product-blazer.png", descricao: "Blazer de corte preciso, ombros estruturados e acabamento de inspiração clássica.", tamanhos: ["P", "M", "G", "GG"] },
  { id: 6, nome: "Camisa Linho Natural", categoria: "Masculino", preco: 690, imagem: "../home/img/product-silk.png", descricao: "Camisa leve de visual natural e caimento confortável para o dia a dia.", tamanhos: ["P", "M", "G", "GG"] },
  { id: 7, nome: "Casaco Ready-to-Wear", categoria: "Masculino", preco: 3290, imagem: "../home/img/ready-to-wear.png", descricao: "Uma camada versátil com desenho limpo e atenção aos detalhes.", tamanhos: ["P", "M", "G", "GG"] },
  { id: 8, nome: "Calça Alfaiataria Grafite", categoria: "Masculino", preco: 1490, imagem: "../home/img/product-blazer.png", descricao: "Calça de alfaiataria com linhas contemporâneas e conforto ao longo do dia.", tamanhos: ["38", "40", "42", "44", "46"] },
  { id: 9, nome: "Conjunto Pequenas Descobertas", categoria: "Infantil", preco: 390, imagem: "../home/img/ready-to-wear.png", descricao: "Conjunto macio e confortável para acompanhar as brincadeiras e descobertas.", tamanhos: ["2", "4", "6", "8", "10"] },
  { id: 10, nome: "Vestido Jardim", categoria: "Infantil", preco: 320, imagem: "../home/img/product-dress.png", descricao: "Vestido infantil leve, com movimento e acabamento confortável.", tamanhos: ["2", "4", "6", "8", "10"] },
  { id: 11, nome: "Camisa Mini Linho", categoria: "Infantil", preco: 240, imagem: "../home/img/product-silk.png", descricao: "Camisa infantil de visual natural, feita para acompanhar toda a rotina.", tamanhos: ["2", "4", "6", "8", "10"] },
  { id: 12, nome: "Casaco Mini Clássico", categoria: "Infantil", preco: 590, imagem: "../home/img/ready-to-wear.png", descricao: "Casaco versátil e aconchegante com inspiração na alfaiataria clássica.", tamanhos: ["2", "4", "6", "8", "10"] },
  { id: 13, nome: "Sapato Derby Clássico", categoria: "Calçados", preco: 1290, imagem: "../home/img/product-blazer.png", descricao: "Sapato de linhas clássicas para compor produções formais e contemporâneas.", tamanhos: ["38", "39", "40", "41", "42", "43"] },
  { id: 14, nome: "Sapatilha Lumière", categoria: "Calçados", preco: 890, imagem: "../home/img/product-dress.png", descricao: "Sapatilha elegante e confortável com desenho minimalista.", tamanhos: ["34", "35", "36", "37", "38", "39"] },
  { id: 15, nome: "Tênis Essencial", categoria: "Calçados", preco: 790, imagem: "../home/img/ready-to-wear.png", descricao: "Tênis de visual limpo para unir conforto e versatilidade.", tamanhos: ["38", "39", "40", "41", "42", "43"] },
  { id: 16, nome: "Mocassim Atelier", categoria: "Calçados", preco: 1490, imagem: "../home/img/product-blazer.png", descricao: "Mocassim de inspiração artesanal com acabamento sofisticado.", tamanhos: ["38", "39", "40", "41", "42", "43"] },
];

function formatarPreco(valor) {
  return valor.toLocaleString("pt-BR", {
    style: "currency",
    currency: "BRL",
  });
}

function obterProduto(id) {
  return produtos.find((produto) => produto.id === Number(id));
}
