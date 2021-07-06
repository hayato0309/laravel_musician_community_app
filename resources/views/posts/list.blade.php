@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1>My posts</h1>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Content</th>
                        <th scope="col">Created at</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <th scope="row">{{$loop->index+1}}</th>
                            <td>{{$post->title}}</td>
                            <td>{{Str::limit($post->content, 50, '...')}}</td>
                            <td>{{$post->created_at}}</td>
                            <td><i class="fas fa-edit"></i></td>
                            <td><i class="fas fa-trash-alt"></i></td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            {{ $posts->links() }}
        </div>
    </div>
</div>

@endsection