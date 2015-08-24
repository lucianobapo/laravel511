<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading text-center">
            <h3 class="h3s text-left">{{ trans('delivery.pedidos.panelGuestTitle') }}</h3>
        </div>
        <div class="panel-body">
            Cadastre-se com o Facebook para participar de nossas promoções!<br>
            {!! link_to_route_social_button('easy.provider', '<i class="fa fa-facebook"></i>'.trans('delivery.nav.loginFacebook'), ['facebook'], ['class' => 'btn btn-social btn-facebook']) !!}
        </div>
    </div>
</div>