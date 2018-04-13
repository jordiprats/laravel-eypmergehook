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

  {{ Form::model($user, array('route' => 'user.update', 'files' => true)) }}
  <div class="row">
    <div class="col-sm-4">
        <img src="https://github.com/{{ $user->nickname }}.png?size=200" class="img-rounded"/>
    </div>
    <div class="col-sm-8">
      <h2>Default settings for puppet modules</h2>
      <hr />
      <div class="form-group">
        {{ Form::label('webhook', 'Enable default webhook') }}
        {{ Form::checkbox('webhook', '1', $user->webhook==1) }}
      </div>
      <div class="form-group">
        {{ Form::label('webhook_password', 'Default webhook password') }}
        {{ Form::text('webhook_password', $user->webhook_password) }}
      </div>
      <hr />
      <div class="form-group">
        {{ Form::label('autoreleasetags', 'Enable creation of releases based on tags') }}
        {{ Form::checkbox('autoreleasetags', '1', $user->autoreleasetags==1) }}
      </div>
      <div class="form-group">
        {{ Form::label('autotagforks', 'Enable auto-tagging for forked repos') }}
        {{ Form::checkbox('autotagforks', '1', $user->autotagforks==1) }}
      </div>
      <hr />
      <div class="form-group">
        {{ Form::label('telegram_notifications', 'Telegram notifications') }}
        {{ Form::checkbox('telegram_notifications', '1', $user->telegram_notifications==1) }}
      </div>

      <div class="form-group">
        {{ Form::label('telegram_chatid', 'Telegram chatid') }}
        {{ Form::text('telegram_chatid', $user->telegram_chatid) }}
      </div>

      <hr />
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
    </div>
  </div>
  {{ Form::submit('Save', array('class'=>'btn-success btn-lg')) }}
  {{ Form::close() }}
</div>
@endsection
