@extends((isset($usePdf)&&$usePdf)?'erp.appPdf':'erp.app')
@section('content')

    <div ng-app="clientSideApp">
        <a href="#login">login</a>
        <div ng-view></div>
    </div>


@endsection
@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular-route.min.js"></script>
    <script type="text/javascript" src="{{ elixir('js/clientSideApp.compiled.js') }}"></script>
@endsection