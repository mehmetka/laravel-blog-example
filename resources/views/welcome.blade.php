@extends('layouts.app')

@section('content')

    <div class="flex-center position-ref full-height">

        <div class="content">

            @foreach ($articles as $key => $article)

                <div class="card">
                    <div class="card-header">
                        {{ $article->title }}

                        <div class="float-right">View count: {{ $article->view_count }}</div>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p>{{ $article->content }}</p>
                            <footer class="blockquote-footer">{{ $article->name }}</footer>
                        </blockquote>
                    </div>
                    <div class="card-footer">
                        Rate: {{$article->rateAverage}}
                        <a class="btn btn-primary float-right" href="{{ route('articles.show', $article->id) }}">Continue
                            to read</a>
                    </div>
                </div>

                <br>

            @endforeach

        </div>
    </div>

@endsection
