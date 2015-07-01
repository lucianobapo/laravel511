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
    ],

    'estoque' => [
        'title' => 'Posição do estoque',
        'id' => 'Id',
        'produto' => 'Produto',
        'estoque' => 'Estoque',
        'total' => 'Total',
        'custoMedioUnitario' => 'Custo médio unitario',
        'custoMedioSubTotal' => 'Custo médio Subtotal',
        'valorVenda' => 'Valor de venda',
    ],
    'estatOrdem' => [
        'title' => 'Estatística de Ordens',
        'titleFinishedOrders' => 'Valores de Ordens Finalizadas',
        'tipo' => 'Tipo de Ordem',
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
        'custoProdutos' => 'Custo dos Produtos',
        'custoMercadorias' => 'Mercadorias',
        'custoLanches' => 'Lanches',
        'margem' => 'Margem de contribuição',
        'despesas' => 'Despesas Operacionais',
        'despesasGerais' => 'Gerais',
        'despesasTransporte' => 'Transporte',
        'ebitda' => 'EBITDA',
        'depreciacao' => 'Depreciação',
        'lucroAntes' => 'Lucro Antes do IRPJ e CSLL',
    ],
);
