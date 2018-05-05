@extends('layouts.app')
@section('content')
<div class="container">
  <h1><ol class="breadcrumb">
    <li class="breadcrumb-item">@include('breadcrumbs.user')</li>
    <li class="breadcrumb-item active">{{ $repo->repo_name }}</li>
  </ol></h1>
  <ul>
    @if($repo->is_puppet_module)
    <li>Puppet module</li>
    @endif
    @if($repo->fork)
    <li>Forked from {{ $repo->fork }}</li>
    @else
    <li>is not a fork</li>
    @endif
    @if($repo->repo_analyzed_on)
    <li>Github repo analyzed {{ Carbon\Carbon::parse($repo->repo_analyzed_on)->diffForHumans() }}</li>
    @else
    <li>Github repo not analyzed</li>
    @endif
  </ul>
  <h2>Releases</h2>
  <ul>
  @foreach ($releases as $release)
    <li>{{ $release->release_name }}</li>
  @endforeach
  </ul>
@endsection
