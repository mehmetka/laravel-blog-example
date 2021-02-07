@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Article</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('main') }}"> Back</a>
                <a class="btn btn-danger" href="{{ route('articles.show',$article->previous) }}"> Previous</a>
                <a class="btn btn-success" href="{{ route('articles.show',$article->next) }}"> Next</a>
            </div>
        </div>
    </div>

    <br>

    <div class="card">
        <div class="card-header">
            {{ $article->title }}
        </div>
        <div class="card-body">
            <blockquote class="blockquote mb-0">
                <p>{{ $article->content }}</p>
                <footer class="blockquote-footer">{{ $article->name }}</footer>
            </blockquote>
        </div>
        <div class="card-footer">

            @guest
                Rate: {{$article->rateAverage}}
            @else
                @if ($article->showRating === true)
                    {!! Form::open(['method' => 'PUT','route' => ['articles.rate', $article->id, 'rate'],'style'=>'display:inline']) !!}
                    {!! Form::select('rate', array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5')); !!}
                    {!! Form::submit('Rate', ['class' => 'btn btn-sm btn-success']) !!}
                    {!! Form::close() !!}
                @else
                    Rate: {{$article->rateAverage}} | Your rate: {{$article->yourRate}}
                @endif
            @endguest

            <div class="float-right">View Count: {{ $article->view_count }}</div>
        </div>
    </div>

@endsection
