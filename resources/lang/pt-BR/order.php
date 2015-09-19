<?php 

return array(

  /*
	|--------------------------------------------------------------------------
	| Order Language Lines
	|--------------------------------------------------------------------------
	|
	|
	*/

	'title' => 'Lista de Ordens',
	'empty' => 'Sem ordens para exibir',
//	'menuOrder' => 'Ordens',
	'listaItens' => 'Lista de Itens',
	'listaEndereco' => 'Entrega',
	'listaAnexos' => 'Anexos',
	'listaAnexosEmpty' => 'Sem Anexos',
	'semEndereco' => 'Sem Endereço Cadastrado',
    'actionTitle' => 'Ações',
    'actionDeleteTitle' => 'Remover',
    'actionEditTitle' => 'Editar',
    'actionDetailsTitle' => 'Detalhes',

    'menu' => [
        'title' => 'Ordens',
        'allOrder' => 'Todas as Ordens',
//        'confirmation' => 'Confirmações',
        'create' => 'Criar Ordem',
        'compras' => 'Ordens de Compra',
        'vendas' => 'Ordens de Venda',
        'abertas' => 'Ordens Abertas',
    ],

    'create' => [
        'createTitle' => 'Criar Ordem',
        'createOrderBtn' => 'Criar Ordem',
        'selecioneParceiro' => 'Selecione um '.trans('modelOrder.attributes.partner_id'),
        'selecioneProduto' => 'Selecione um '.trans('modelItemOrder.attributes.product_id'),
        'selecioneCusto' => 'Selecione um '.trans('modelItemOrder.attributes.cost_id'),
        'selecioneEndereco' => 'Selecione um '.trans('modelOrder.attributes.address_id'),
    ],
    'update' => [
        'title' => 'Atualizar Ordem nº:numero',
        'orderBtn' => 'Atualizar Ordem',
//        'selecioneParceiro' => 'Selecione um '.trans('modelOrder.attributes.partner_id'),
//        'selecioneProduto' => 'Selecione um '.trans('modelItemOrder.attributes.product_id'),
//        'selecioneCusto' => 'Selecione um '.trans('modelItemOrder.attributes.cost_id'),
//        'selecioneEndereco' => 'Selecione um '.trans('modelOrder.attributes.address_id'),
    ],

    'flash' => [
        'orderCreated' => 'Ordem nº :ordem criada com sucesso!',
        'orderUpdated' => 'Ordem nº :ordem atualizada com sucesso!',
        'orderDeleted' => 'Ordem nº :ordem removida com sucesso!',
        'orderCreatedTitle' => 'Ordem criada',
        'orderUpdatedTitle' => 'Ordem atualizada',
        'orderDeletedTitle' => 'Ordem removida',
    ], // flash
);
