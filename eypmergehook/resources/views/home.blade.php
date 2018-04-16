@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                @if(empty($user->name))
                <div class="panel-heading">{{ $user->nickname }}</div>
                @else
                <div class="panel-heading">{{ $user->nickname }} ({{ $user->name }})</div>
                @endif
                <div class="panel-body">
                  @if(Auth::user()==$user)
                  {!! Form::open(['route' => 'platforms.create', 'method' => 'get']) !!}
                    {{ Form::submit('Create new platform', array('class'=>'btn-success btn-lg', 'style'=>'float:right')) }}
                  {!! Form::close() !!}
                  @endif
                  <h2>Platforms</h2>
                  <ul>
                    @foreach ($platforms as $platform)
                    <li>@include('breadcrumbs.platform')</li>
                    @endforeach
                  </ul>
                  <h2>Repos</h2>
                  <ul>
                    @foreach ($repos as $repo)
                    <li>@include('breadcrumbs.repos')</li>
                    @endforeach
                  </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
