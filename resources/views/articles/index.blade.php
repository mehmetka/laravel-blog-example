@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Articles Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('articles.create') }}"> Create New Article</a>
            </div>
        </div>
    </div>

    <br>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered">
        <tr>
            <th>Title</th>
            <th>View Count</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($articles as $key => $article)
            <tr>
                <td>{{ $article->title }}</td>
                <td>{{ $article->view_count }}</td>
                <td>
                    <a class="btn btn-primary" href="{{ route('articles.edit',$article->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['articles.delete', $article->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}

                    @if ($article->publish === 0)
                        {!! Form::open(['method' => 'PUT','route' => ['articles.publish', $article->id, 'publish'],'style'=>'display:inline']) !!}
                        {!! Form::submit('Publish', ['class' => 'btn btn-success']) !!}
                        {!! Form::close() !!}
                    @else
                        {!! Form::open(['method' => 'PUT','route' => ['articles.publish', $article->id, 'unpublish'],'style'=>'display:inline']) !!}
                        {!! Form::submit('Unpublish', ['class' => 'btn btn-warning']) !!}
                        {!! Form::close() !!}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

@endsection
