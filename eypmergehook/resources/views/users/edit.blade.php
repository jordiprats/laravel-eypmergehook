@extends('layouts.app')

@section('content')
<div class="container">
  @if(Session::has('status'))
  <div class="alert {{ Session::get('status-class') }}">
    <strong>{{ Session::get('status') }}</strong>
  </div>
  @endif
  <h1>profile</h1>
  <hr/>

  <div class="row">
  {{ Form::model($user, array('route' => 'user.update', 'files' => true)) }}
    <div class="col-*-*">
        <img src="https://github.com/{{ $user->nickname }}.png?size=200" class="img-rounded"/>
        <input name="avatar" id="file-input" type="file" style="display: none" />
      </div>
    </div>
    <hr />
    <div class="col-*-*">
      <p>
        <ul>
          <!-- github repos -->
          @if($user->github_repos_updated_on)
          <li>Github repos last synced about {{ Carbon\Carbon::parse($user->github_repos_updated_on)->diffForHumans() }}</li>
          @else
          <li>Github repos not synced</li>
          @endif
          <!-- github orgs -->
          @if($user->github_organizations_updated_on)
          <li>Github organitzations last synced about {{ Carbon\Carbon::parse($user->github_organizations_updated_on)->diffForHumans() }}</li>
          @else
          <li>Github organitzations not synced</li>
          @endif
        </ul>
      <hr />
      {{ Form::submit('Save', array('class'=>'btn-success btn-lg')) }}
    </div>
  {{ Form::close() }}
  </div>
</div>
@endsection
