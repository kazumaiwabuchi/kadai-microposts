{{--当該投稿のidが閲覧者のis_favorite()内にあれば（お気に入り追加済みなら）、お気に入り削除ボタン表示--}}
@if (Auth::user()->is_favorite($micropost->id))
    {{-- お気に入り削除ボタンのフォーム --}}
    {!! Form::open(['route' => ['favorites.unfavorite', $micropost->id], 'method' => 'delete']) !!}
        {!! Form::submit('お気に入りを外す', ['class' => "btn btn-danger btn-block"]) !!}
    {!! Form::close() !!}
{{--お気に入り未追加なら、お気に入りボタン表示--}}
@else
    {{-- フォローボタンのフォーム --}}
    {!! Form::open(['route' => ['favorites.favorite', $micropost->id]]) !!}
        {!! Form::submit('お気に入り追加', ['class' => "btn btn-primary btn-block"]) !!}
    {!! Form::close() !!}
@endif
