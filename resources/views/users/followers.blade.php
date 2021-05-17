@extends('layouts.app')//共通レイアウト継承

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            {{-- ユーザ情報 --}}
            @include('users.card')
        </aside>
        <div class="col-sm-8">
            {{-- タブ --}}
            @include('users.navtabs')
            {{-- ユーザ一覧 --}}
            @include('users.users')//フォロワー一覧が渡され、一覧表示される
        </div>
    </div>
@endsection