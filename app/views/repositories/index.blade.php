@extends('layouts.default')

@section('content')
  @foreach($data as $repo)
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title" style="font-weight:bold;">
          <img class="repo-owner-avatar" src="{{ $repo['avatar_url'] }}" alt="Created by {{ $repo['owner'] }}" />
          <a href="https://github.com/{{ $repo['full_name'] }}" target="_blank">{{ $repo['full_name'] }}</a>
          <span class="right">
            <span class="glyphicon glyphicon-calendar"></span>
            {{ date('Y-m-d H:i:s', strtotime($repo['created'])) }}
          </span>
        </h3>
      </div>
      <div class="panel-body">
        <span style="font-weight:bold;">Description:</span>
        &nbsp;{{ $repo['description'] }}
        <hr></hr>
        <div class="left">
          <ul class="repo-blocks-list">
            <li class="repo-block-item">
              <span class="glyphicon glyphicon-star"></span>
              <span class="value">{{ $repo['stars'] }}</span>
              <span class="text">Stars</span>
            </li>
            <li class="repo-block-item">
              <span class="glyphicon glyphicon-eye-open"></span>
              <span class="value">{{ $repo['watchers'] }}</span>
              <span class="text">Watchers</span>
            </li>
            <li class="repo-block-item">
              <span class="glyphicon glyphicon-cutlery"></span>
              <span class="value">{{ $repo['forks'] }}</span>
              <span class="text">Forks</span>
            </li>
            <li class="repo-block-item">
              <span class="glyphicon glyphicon-list"></span>
              <span class="value">{{ $repo['open_issues'] }}</span>
              <span class="text">Issues</span>
            </li>
          </ul>
        </div>
        <div class="left" style="margin-left: 25px;">
          <div class="well well-sm repo-owner-container">
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection
