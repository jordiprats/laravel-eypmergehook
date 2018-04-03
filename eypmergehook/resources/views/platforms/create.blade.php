@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Create a new platform</h1>
  <hr/>
  {!! Form::open(['route' => 'platforms.store']) !!}
    {{ Form::label('platform_name', 'Platform name:') }}
    {{ Form::text('platform_name', null, array('class' => 'form-control')) }}
    {{ Form::label('description', 'Short description:') }}
    {{ Form::text('description', null, array('class' => 'form-control')) }}

    {{ Form::submit('Create platform', array('class'=>'btn-success btn-lg btn-block', 'style' => 'margin-top: 20px;')) }}
  {!! Form::close() !!}
</div>
@endsection
