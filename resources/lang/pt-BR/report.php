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
	'menuName' => 'Relatórios',
	'menuEstoque' => 'Estoque',
	'menuEstatOrdem' => 'Estatística de Ordens',
//	'actionTitle' => 'Ações',
//	'actionBtn' => 'Adicionar',
//	'actionDeleteTitle' => 'Remover',
//	'productCreated' => 'Produto Criado com sucesso!',
//	'productCreatedTitle' => 'Produto Criado',
//	'productDeleted' => 'Produto Removido com sucesso!',
//	'productDeletedTitle' => 'Produto Removido',

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
);
