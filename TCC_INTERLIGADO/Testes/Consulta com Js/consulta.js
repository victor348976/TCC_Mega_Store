const idProduto = 1;

const sqlDetalhes = `
  SELECT 
    p.id_produto,
    p.nome_produto,
    p.descricao_produto,
    p.preco,
    c.nome_categoria,
    g.nome_genero,
    v.id_variacao,
    v.tamanho,
    v.cor,
    v.estoque,
    i.caminho_imagem
  FROM tb_produto p
  INNER JOIN tb_categoria c ON p.id_categoria = c.id_categoria
  INNER JOIN tb_genero g ON p.id_genero = g.id_genero
  LEFT JOIN tb_variacao_produto v ON p.id_produto = v.id_produto
  LEFT JOIN tb_imagem_produto i ON v.id_variacao = i.id_variacao
  WHERE p.id_produto = ? AND p.ativo = 1
`;

const [detalhes] = await conexao.execute(sqlDetalhes, [idProduto]);
console.log(detalhes);

