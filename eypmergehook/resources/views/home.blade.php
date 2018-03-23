@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $user->username }} ({{ $user->name }})</div>
                <div class="panel-body">
                  @if(Auth::user()==$user)
                  {!! Form::open(['route' => 'platform.create', 'method' => 'get']) !!}
                    {{ Form::submit('Create new platform', array('class'=>'btn-success btn-lg', 'style'=>'float:right')) }}
                  {!! Form::close() !!}
                  @endif
                  <h1>Platforms</h1>
                  <ul>
                    @foreach ($platforms as $platform)
                    <li>@include('breadcrumbs.platform')</li>
                    @endforeach
                  </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
