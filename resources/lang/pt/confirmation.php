<?php 

return array(

  /*
	|--------------------------------------------------------------------------
	| Confirmations Language Lines
	|--------------------------------------------------------------------------
	|
	|
	*/

	'title' => 'Lista de Ordens Abertas',
//	'empty' => 'Sem parceiros para exibir',
	'menuName' => 'Confirmações',
	'actionTitle' => 'Ações',
//	'actionAddBtn' => 'Adicionar',
//	'actionUpdateBtn' => 'Atualizar',
//	'actionDeleteTitle' => 'Remover',
	'actionEditTitle' => 'Confirmar Ordem',

    'confirm' =>[
        'title' => 'Confirmar Ordem nº:ordem',
        'posted_at' => 'Ordem postada em',
        'recebido' => [
            'btn' => 'Confirmar Recebimento do Pedido',
            'label' => 'Mensagem',
            'enviarMensagem' => 'Enviar email ao usuário',
            'msg' => 'Olá, recebemos seu pedido, estamos providenciando a entrega.',
        ],
    ],

    'btn' => [
        'producao',
        'pronto',
        'entregando',
        'entregue',
        'pago',
    ],

    'flash' => [
        'confirmed' => 'Ordem nº:ordem confirmada com sucesso!',
        'confirmedTitle' => 'Ordem confirmada',
//        'updated' => 'Parceiro :nome atualizado com sucesso!',
//        'updatedTitle' => 'Parceiro atualizado',
//        'deleted' => 'Parceiro :nome removido com sucesso!',
//        'deletedTitle' => 'Parceiro removido',
    ], // flash
);