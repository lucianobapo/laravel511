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
	'empty' => 'Sem ordens para exibir',
	'menuName' => 'Confirmações',
	'actionTitle' => 'Ações',
//	'actionAddBtn' => 'Adicionar',
//	'actionUpdateBtn' => 'Atualizar',
//	'actionDeleteTitle' => 'Remover',
	'actionEditTitle' => 'Editar Ordem',
	'actionConfirmTitle' => 'Confirmar Ordem',

    'confirm' =>[
        'title' => 'Confirmar Ordem nº:ordem',
        'posted_at' => 'Ordem postada em',
        'recebido' => [
            'btn' => 'Confirmar Recebimento do Pedido',
            'label' => 'Mensagem',
            'enviarMensagem' => 'Enviar email ao usuário',
            'msg' => 'Olá, recebemos seu pedido, estamos providenciando a entrega.',
        ],
        'entregando' => [
            'btn' => 'Entregando o Pedido',
            'label' => 'Km de saida',
            'posted_at' => 'Data/Hora',
//            'enviarMensagem' => 'Enviar email ao usuário',
            'msg' => 'Ex.: 8769 km',
        ],
        'entregue' => [
            'btn' => 'Pedido entregue',
            'label' => 'Km de chegada',
            'posted_at' => 'Data/Hora',
//            'enviarMensagem' => 'Enviar email ao usuário',
            'msg' => 'Ex.: 8944 km',
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
