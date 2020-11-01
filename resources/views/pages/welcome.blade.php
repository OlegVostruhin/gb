@extends('main')

@section('title', 'Main Page')

@section('content')

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <!--FORM-->
                    {!! Form::open(['data-route' => 'posts', 'class' => 'form-horizontal', 'id' => 'form-data']) !!}
                    <div class="form-group">
                        {{ Form::label('post_text', "Сообщение: ", ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-10">
                            {{ Form::textarea('post_text', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            {{ Form::submit('Отправить сообщение', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                {!! Form::close() !!}
                <!-- END FORM -->
                </div>
            </div>
            <div class="post_list row"> <!-- POSTS -->
                @if (isset($posts))
                    @foreach($posts as $post)
                        <div class="post col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>{{ isset($post->user) ? $post->user->name : "Аноним"}}</h4>
                                    <h6>{{ date('M j, Y H:i', strtotime($post->updated_at)) }}</h6>
                                    <p>{{ isset($post) ? $post->text : 'Новый пост' }}</p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="btn-group" role="group">
                                        @if(Auth::check())
                                            @if(Auth::user()->hasRole('user') && $post->user_id == Auth::user()->id)
                                                @if (abs(strtotime('now')-strtotime($post->created_at)) < (60 * 60 * 2))
                                                    {!! Html::linkRoute('posts.edit', 'Редактировать',
                                                        $post->id,
                                                        array(
                                                            'class' => 'btn btn-warning',
                                                            'data-post-id' => $post->id
                                                            ))
                                                    !!}
                                                    @if (abs(strtotime('now')-strtotime($post->created_at)) < (60 * 60 * 2))
                                                        {!! Html::linkRoute('posts.destroy', 'Удалить',
                                                            $post->id,
                                                            array(
                                                                'class' => 'btn btn-danger',
                                                                'data-post-id' => $post->id
                                                            ))
                                                        !!}
                                                    @endif
                                                @endif
                                            @elseif(Auth::user()->hasRole('admin'))
                                                {!! Html::linkRoute('posts.edit', 'Редактировать',
                                                    $post->id,
                                                    array(
                                                        'class' => 'btn btn-warning',
                                                        'data-post-id' => $post->id
                                                        ))
                                                !!}
                                                {!! Html::linkRoute('posts.destroy', 'Удалить',
                                                    $post->id,
                                                    array(
                                                        'class' => 'btn btn-danger',
                                                        'data-post-id' => $post->id
                                                    ))
                                                !!}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <hr/>
                        </div> <!-- POST BLOCK -->
                    @endforeach
                    @if(Auth::check() && Auth::user()->hasRole('admin'))
                    <a href="{{ route('export') }}"><button class="btn-success">Export Excel</button></a>
                    @endif
                @endif
            </div>
        </div>
    </main>
@endsection
