@extends('layouts.app')
@section('content')
<div class="container">
  @if(Auth::user()==$user)
    @if($platform->status!=0)
  <div class="card" style="float: right;">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal3" style="display: inline-block;">
      Delete platform
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModal3Label" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button btn-secondary" class="close" data-dismiss="modal" aria-label="Close">
              X
            </button>
            <h5 class="modal-title" id="exampleModal3Label">Delete platform</h5>
          </div>
          <div class="modal-body">
            Once you delete a platform, there is no going back. Please be certain.
            <hr />
            {!! Form::open(['route' => ['platforms.destroy', $platform->id], 'method' => 'delete']) !!}
              {{ Form::submit('Yes, I\'m sure to delete this platform', array('class'=>'btn-danger btn-lg')) }}
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>

    <div class="dropdown" style="display: inline-block;">
      <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">Actions <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="{{ route('environments.create', [$user->username, $platform->platform_name]) }}">Create environment</a></li>
        <li><a href="{{ route('servertypes.create', [$user->username, $platform->platform_name]) }}">Create server type</a></li>
        <li><a href="{{ route('servergroups.create', [$user->username, $platform->platform_name]) }}">Create server group</a></li>
      </ul>
    </div>
  </div>
    @else
  <div style="float: right;">
    <button class="btn btn-secondary disabled" type="button">No Actions available </span></button>
  </div>
    @endif
  @endif
  <h1><ol class="breadcrumb">
    <li class="breadcrumb-item">@include('breadcrumbs.user')</li>
    <li class="breadcrumb-item active">{{ $platform->platform_name }}</li>
  </ol></h1>
  <p>{{ $platform->description }}</p>
  <p>{{ $platform->eyp_userid }} / {{ $platform->eyp_magic_hash }} </p>
  @if($platform->status!=0)
  <p>Platform ready</p>
  @else
  <p><i class="fa fa-circle-o-notch fa-spin"></i> Please wait while we are creating you platform</p>
  @endif
  @if(count($platform->environments)=='')
  <h3>No environments defined</h3>
  @else
    <h3>Environments</h3>
    <ul>
    @foreach ($platform->environments as $environment)
    <li><a href="{{ route('show.eyp.user.platform.env', ['user' => $user->username, 'platform' => $platform->slug, 'environment' => $environment->slug]) }}">{{ $environment->environment_name }}</a></li>
    @endforeach
  </ul>
  @endif
  <h3>Server groups</h3>
  <ul>
    <li>...</li>
  </ul>
  <h3>Server types</h3>
  <ul>
    <li>...</li>
  </ul>
  <h3>Nodes</h3>
  <ul>
    <li>...</li>
  </ul>
</div>
@endsection
