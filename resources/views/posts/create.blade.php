@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>Create a post</h1>
            <form action="{{route('post.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control {{$errors->has('title')?'is-invalid':''}}" name="title" placeholder="Please write the title." value="">
                    
                    @if($errors->has('title'))
                        <p class="text-danger">{{$errors->first('title')}}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label>Content</label>
                    <textarea type="text" class="form-control {{$errors->has('content')?'is-invalid':''}}" name="content" cols="30" rows="10" placeholder="Please write the content." value=""></textarea>
                    
                    @if($errors->has('content'))
                        <p class="text-danger">{{$errors->first('content')}}</p>
                    @endif
                </div>
                
                <div class="form-group">
                    <input type="file" class="form-control-file mt-2" name="post_image">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

@endsection