@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    本页面用来测试passport web middleware; 这个包 允许前端无缝的方式api
                </div>
            </div>
        </div>
        <div>
            <passport-web-middleware-show></passport-web-middleware-show>
        </div>
    </div>
@endsection