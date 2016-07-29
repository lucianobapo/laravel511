<?php 

return array(

  /*
	|--------------------------------------------------------------------------
	| Delivery Language Lines
	|--------------------------------------------------------------------------
	|
	|
	*/

    'clickOnce' => 'Carregando...',
    'deliveryFechado' => [
        'productFormAlias' => 'Indisponível',
        'errorTitle' => 'Aviso!',
        'errorText' => 'Pedidos suspensos temporariamente para manutenção de estoque. Retorno previsto para :retorno.',
    ],

    'nav' => [
        'login' => 'Entrar',
        'loginFacebook' => 'Acessar com Facebook',
        'register' => 'Registrar',
        'cartHeader' => 'Carrinho de Compras',
        'cartEmpty' => 'Carrinho vazio.',
        'cartTotal' => 'Valor Total',
        'cartBtn' => 'Fechar o Pedido',
        'logoAlt' => 'Logomarca do site',
//        'logoTitle' => 'SeuBoteco.com',
        'logoTitle' => config('delivery.siteCurrentUrl'),//'delivery.ilhanet.com',
    ],

    'head' => [
        'title' => 'Delivery Rio das Ostras 24 horas - Entrega de Bebidas e Cigarros',
        'metaAuthor'=>'Luciano Porto - ilhanet.com',
        'metaRobots'=>'index, follow',
        'metaDescription' => 'Delivery em Rio das Ostras 24 horas - Frete Grátis,
        com entrega de bebidas, cigarros,
        vinhos e vodka, aberto 24 horas.',
    ],

    'categorias' => [
        'itensEmpty' => 'Não disponíveis no momento',
        'porcoesTitle' => 'Porções',
        'lanchesTitle' => 'Lanches',
        'cervejasTitle' => 'Cervejas',
        'vinhosTitle' => 'Vinhos',
        'destiladosTitle' => 'Destilados',
        'refrigerantesTitle' => 'Refrigerantes',
        'energeticosTitle' => 'Energéticos',
        'tabacariaTitle' => 'Tabacaria',
        'sucosTitle' => 'Sucos',
        'outrosTitle' => 'Outros',

    ],

    'index' => [
        'title' => 'Delivery Rio das Ostras 24 horas',
        'subTitle' => 'Frete grátis na entrega de Bebidas e Cigarros.',
//        'panelTitle' => 'Faça o pedido e receba em sua casa.',
        'semProdutos' => 'Nenhum produto para exibir',
        'email-is-already-in-use' => 'Email do usuário já está cadastrado em nosso sistema com outras credenciais.',
        'errorTitle' => 'Aviso!',
        'errorText' => 'Infelizmente não foi possível entrar.',
//        'published_at' => 'Publicado Em',
    ], // index

    'pedidos' => [
//        'title' => 'Delivery Rio das Ostras 24 horas',
        'subTitle' => 'Confira seu pedido e solicite a entrega',
        'panelTitle' => 'Resumo das compras',
        'panelEntregaTitle' => 'Dados de entrega',
        'panelGuestTitle' => 'Ainda não possui cadastro?',
        'cartEmpty' => 'Carrinho vazio.',
        'valorTotal' => 'Valor total',
        'continueBtn' => 'Continue comprando',
        'finalizarBtn' => 'Enviar para Entrega',
        'emptyBtn' => 'Limpar Carrinho',
        'form' => [
            'formaPagamento' => 'Forma de Pagamento',
            'dadosPessoais' => 'Dados Pessoais',
            'dadosContato' => 'Dados de Contato',
            'dadosEntrega' => 'Endereço de entrega',
            'requiredTag' => 'Campos obrigatórios',
            'requiredTag2' => 'Pelo menos um dos campos devem ser preenchidos',
            'oldAddress' => 'Endereços utilizados anteriormente',
            'createAddress' => 'Cadastrar Novo endereço',
            'placeholder' => [
                'cep' => '28890001 a 28899999',
                'data_nascimento' => 'dd/mm/aaaa',
                'troco' => 'Digite aqui a quantia que irá pagar',
            ],
        ],
    ], // pedidos

    'productBlock' => [
        'imageAlt' => 'Imagem do produto :product',
        'formAddButton' => 'Adicionar',
        'tooltip' => 'Item adicionado com sucesso!',
    ], // productBlock

    'flash' => [
        'itemAdd' => 'Item adicionado com sucesso!',
        'pedidoAdd' => 'Seu pedido nº :pedido foi adicionado com sucesso! Você receberá o acompanhamento pelo email ":email".',
    ], // flash

    'dataLabels' => [
        ''
    ],
);
