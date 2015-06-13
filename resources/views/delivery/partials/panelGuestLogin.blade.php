<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading text-center">
            <h3 class="h3s">{{ trans('delivery.pedidos.panelGuestTitle') }}</h3>
        </div>
        <div class="panel-body">
            Cadastre-se com o Facebook para participar de nossas promoções e ter o histórico dos seus pedidos.
            {!! link_to('/easyAuth/facebook', trans('delivery.nav.loginFacebook'), $host) !!}
        </div>
    </div>
</div>