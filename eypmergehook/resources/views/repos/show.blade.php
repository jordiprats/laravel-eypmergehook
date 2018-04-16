@extends('layouts.app')
@section('content')
<div class="container">
  <h1><ol class="breadcrumb">
    <li class="breadcrumb-item">@include('breadcrumbs.user')</li>
    <li class="breadcrumb-item active">{{ $repo->repo_name }}</li>
  </ol></h1>
  <ul>
  @foreach ($releases as $release)
    <li>{{ $release->release_name }}</li>
  @endforeach
  </ul>
@endsection
