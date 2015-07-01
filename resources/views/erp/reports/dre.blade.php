@extends('erp.app')
@section('content')
    <h1 class="h1s">{{ trans('report.dre.title') }}</h1>
    <hr>
    <table class="table table-hover table-condensed" ng-app="myApp">
        <thead>
            <tr>
                <th>{{ trans('report.dre.estrutura') }}</th>
                @foreach($periodos as $periodo)
                    <th>{{ $periodo['title'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
@endsection
@section('footerScriptJs')
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.3.15/angular.min.js"></script>
    <script type="text/javascript">
        var app = angular.module('myApp', []);
        //        app.controller('myCtrl', function($scope) {
        //            //            $scope.firstName= "John";
        //            //            $scope.lastName= "Doe";
        ////            $scope.list = [];
        ////            $scope.text = 'hello';
        //            $scope.submit = function() {
        //                if ($scope.text) {
        //                    $scope.list.push(this.text);
        //                    $scope.text = '';
        //                }
        //            };
        //        });
    </script>
@endsection