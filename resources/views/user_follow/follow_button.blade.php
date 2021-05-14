@if (Auth::id() != $user->id) //認証済みユーザ（閲覧者）が自分自身ではないとき、ボタンを表示する
    @if (Auth::user()->is_following($user->id))//当該ユーザのidが閲覧者のis_following()内にあれば（フォロー済みなら）、アンフォローボタン表示
        {{-- アンフォローボタンのフォーム --}}
        {!! Form::open(['route' => ['user.unfollow', $user->id], 'method' => 'delete']) !!}
            {!! Form::submit('Unfollow', ['class' => "btn btn-danger btn-block"]) !!}
        {!! Form::close() !!}
    @else//未フォローなら、フォローボタン表示
        {{-- フォローボタンのフォーム --}}
        {!! Form::open(['route' => ['user.follow', $user->id]]) !!}
            {!! Form::submit('Follow', ['class' => "btn btn-primary btn-block"]) !!}
        {!! Form::close() !!}
    @endif
@endif