@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @if(session('comment-posted-message'))
                <div class="alert alert-success">{{ session('comment-posted-message') }}</div>
            @endif

            {{-- Breadcrumb list --}}
            <div class="mb-4">
                <a href="{{ route('home') }}" class="text-body"><i class="fas fa-home"></i></a>
                <i class="fas fa-caret-right"></i>
                <a href="#" class="text-body">{{$post->title}}</a>
            </div>
            
            <div class="mb-3">
                <h1 class="mb-4">{{ $post->title }}</h1>
                <div class="mb-4">{{ $post->content }}</div>
                <div class="text-right mb-4">
                    <div class="d-inline mr-3">
                        <i class="far fa-heart text-muted"></i>
                        <span class="text-muted">123</span>
                    </div>
                    <div class="d-inline text-muted">Posted by <a href="#" class="text-muted">{{ $post->user->name }}</a> {{ $post->created_at->diffForHumans() }}</div>
                </div>
                @if($post->post_image)
                    <img class="rounded" src="{{ asset('storage/'.$post->post_image) }}" alt="avatar" style="width:30%">
                @endif
            </div>
            
            {{-- Comments display area --}}
            @foreach ($comments as $comment)
                <div>
                    <div class="rounded shadow-sm mb-3 p-3">
                        <div class="mb-1">
                            <img class="rounded-circle float-left mr-2" src="{{ asset('storage/'.$comment->user->avatar) }}" alt="comment-user-image" style="width:45px">
                            <div class="d-inline">
                                <div>{{ $comment->user->name }}</div>
                                <div class="text-muted">{{ $comment->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="border-left px-3">{{ $comment->content }}</div>
                    </div>
                </div>
            @endforeach

            {{-- Comments Textarea --}}
            <div>
                <form action="{{ route('comment.store', $post->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="text-muted text-right">
                        <i class="far fa-comment-alt"></i>
                        <span>Comment</span>
                    </div>
                    <textarea type="text" class="form-control mb-2 {{ $errors->has('current_password')?'is-invalid':'' }}" name="content" cols="30" rows="3" placeholder="Please write your comment.">{{ old('content') }}</textarea>
                    @if($errors->has('content'))
                        <p class="text-danger">{{ $errors->first('content') }}</p>
                    @endif
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection