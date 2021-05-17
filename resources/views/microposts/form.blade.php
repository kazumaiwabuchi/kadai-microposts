{!! Form::open(['route' => 'microposts.store']) !!}
    <div class="form-group">
        {!! Form::textarea('content', null, ['class' => 'form-control', 'rows' => '2']) !!}
        {!! Form::submit('投稿する', ['class' => 'btn btn-primary btn-block']) !!}
    </div>
{!! Form::close() !!}