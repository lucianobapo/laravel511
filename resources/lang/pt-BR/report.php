<?php 

return array(

  /*
	|--------------------------------------------------------------------------
	| Reports Language Lines
	|--------------------------------------------------------------------------
	|
	|
	*/

//	'title' => 'Lista de Parceiros',
//	'empty' => 'Sem parceiros para exibir',

//	'actionTitle' => 'Ações',
//	'actionBtn' => 'Adicionar',
//	'actionDeleteTitle' => 'Remover',
//	'productCreated' => 'Produto Criado com sucesso!',
//	'productCreatedTitle' => 'Produto Criado',
//	'productDeleted' => 'Produto Removido com sucesso!',
//	'productDeletedTitle' => 'Produto Removido',

    'menu' => [
        'topName' => 'Relatórios',
        'estoque' => 'Estoque',
        'estatOrdem' => 'Estatística de Ordens',
        'dre' => 'DRE',
        'diarioGeral' => 'Diário Geral',
        'cardapio' => 'Cardápio',
    ],

    'estoque' => [
        'title' => 'Posição do estoque',
        'id' => 'Id',
        'produto' => 'Produto',
        'compras' => 'Compras',
        'vendas' => 'Vendas',
        'margem' => 'Margem Bruta',
        'estoque' => 'Estoque',
        'estoqueMinimo' => 'Estoque Mínimo',
        'total' => 'Total',
        'custoMedioUnitario' => 'Custo médio unitario',
        'custoMedioSubTotal' => 'Custo em Estoque',
        'valorVenda' => 'Valor de venda',
        'valorVendaSubTotal' => 'Venda em Estoque',
    ],

    'cardapio' => [
        'title' => 'Cardápio',
        'reportTime' => 'Relatorio de :tempo',
        'id' => 'Número',
        'produto' => 'Produto',
        'categoria' => 'Categoria',
        'valorVenda' => 'Valor',
    ],

    'estatOrdem' => [
        'title' => 'Estatística de Ordens',
        'titleFinishedOrders' => 'Valores de Ordens Finalizadas',
        'tipo' => 'Tipo de Ordem',
        'panelOrdemVenda' => 'Ordens de Venda Finalizadas',
        'tipos' => [
            'totalOrder' => 'Todas as Ordens',
            'cancelledOrders' => 'Ordens Canceladas',
            'openedOrders' => 'Ordens Abertas',
            'finishedOrders' => 'Ordens Finalizadas',
            'totalVenda' => 'Ordens de Venda Finalizadas',
            'totalCompra' => 'Ordens de Compra Finalizadas',
            'totalVendaEntregue' => 'Ordens de Venda Finalizadas e Entregues',
        ],
        'quantidade' => 'Quantidade',
        'porcentagem' => 'Porcentagem',
        'valoresMensais' => [
            'mes' => 'Mês',
            'valorVenda' => 'Total de Venda',
            'valorCompra' => 'Total de Compra',
            'debitoFinanceiro' => 'Total de Débito Financeiro',
            'creditoFinanceiro' => 'Total de Crédito Financeiro',
            'diferenca' => 'Saldo',
        ],
        'tableOrdensPorDia' => [
            'title' => 'Ordens x Dia do Mês',
            'diaMes' => 'Dia do Mês',
            'valor' => 'Valor',
            'quantidade' => 'Quantidade',
            'soma' => 'Total',
        ],
        'tableOrdensPorMes' => [
            'title' => 'Ordens x Mês',
            'diaMes' => 'Mês',
            'quantidade' => 'Quantidade',
            'valor' => 'Valor',
            'soma' => 'Total',
        ],
        'tableOrdensPorSemana' => [
            'title' => 'Ordens x Dia da Semana',
            'semana' => 'Dia da Semana',
            'quantidade' => 'Quantidade',
            'valor' => 'Valor',
            'soma' => 'Total',
        ],
        'tableOrdensPorHora' => [
            'title' => 'Ordens x Hora do Dia',
            'hora' => 'Hora',
            'quantidade' => 'Quantidade',
            'valor' => 'Valor',
            'soma' => 'Total',
        ],
    ],

    'dre' => [
        'title' => 'DRE - Demonstrativo de Resultados do Exercício',
        'estrutura' => 'Estrutura',
        'receitaLiquida' => 'Receita de Vendas Líquida',
        'receitaBruta' => 'Receita de Vendas Bruta',
        'receitaBrutaDinheiro' => 'Vendas Dinheiro',
        'receitaBrutaCartaoDebito' => 'Vendas Cartão de Débito',
        'receitaBrutaCartaoCredito' => 'Vendas Cartão de Crédito',
        'deducaoReceita' => 'Deduções da Receita',
        'honorariosPedidosJa' => 'Honorários do Pedidos Já',
        'imposto' => 'Imposto MEI',
        'honorariosPayleven' => 'Honorários de Cartão Payleven',
        'honorariosPaylevenCredito' => 'Cartão de Crédito',
        'honorariosPaylevenDebito' => 'Cartão de Débito',
        'custo' => [
            'title' => 'Custo dos Produtos',
            'consumo' => 'Consumo médio do Estoque',
            'custoMercadorias' => 'Mercadorias',
            'custoLanches' => 'Lanches',
        ],

        'margem' => 'Margem de contribuição',

        'despesas' => [
            'title' => 'Despesas Operacionais',
            'despesasGerais' => 'Gerais',
            'despesasMensaisFixas' => 'Mensais Fixas',
            'despesasMarketingPropaganda' => 'Marketing e Propaganda',
            'despesasTransporte' => 'Transporte',
        ],

        'ebitda' => 'EBITDA',
        'depreciacao' => 'Depreciação',
        'estoque' => [
            'subtotal' => 'Estoque',
            'acumulado' => 'Acumulado',
            'compras' => 'Compras',
            'mercadorias' => 'Mercadorias',
            'lanches' => 'Lanches',
            'consumo' => 'Consumo médio',
            'saldo' => 'Saldo',
        ],
        'lucroAntes' => 'Lucro Antes do IRPJ e CSLL',
    ],
    'diarioGeral' => [
        'title' => 'Diário Geral',
        'data' => 'Data',
        'contaDebitada' => 'Conta Debitada',
        'contaCreditada' => 'Conta Creditada',
        'valor' => 'Valor',
        'transacao' => 'Transação',
    ],


);
