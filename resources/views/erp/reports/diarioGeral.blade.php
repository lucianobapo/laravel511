@extends('erp.app')
@section('contentWide')
    <h1 class="h1s">{{ trans('report.diarioGeral.title') }}</h1>
    <hr>
    {!! $viewTableOrdens !!}
@endsection