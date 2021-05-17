@extends('layouts.app')//共通レイアウト継承

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            {{-- ユーザ情報 --}}
            @include('users.card')
        </aside>
        <div class="col-sm-8">
            {{-- タブ --}}
            @include('users.navtabs')//ナビタブ
            {{-- ユーザ一覧 --}}
            @include('users.users')//ユーザ一覧ページの継承,フォロー中ユーザ一覧が渡される
        </div>
    </div>
@endsection
