<table class="table table-hover table-condensed" ng-app="myApp">
    <thead>
    <tr>
        <th>{{ trans('report.estatOrdem.tipo') }}</th>
        <th>{{ trans('report.estatOrdem.quantidade') }}</th>
        <th>{{ trans('report.estatOrdem.porcentagem') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key=>$value)
        <tr>
            <td>{{ $key }}</td>
            <td>{{ $value }}</td>
            <td>{{ $percentage[$key] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>