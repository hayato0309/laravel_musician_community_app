@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Breadcrumb list --}}
            <div class="mb-4">
                <a href="{{ route('home') }}" class="text-body"><i class="fas fa-home"></i></a>
                <i class="fas fa-caret-right"></i>
                <a href="#" class="text-body">{{ $user->name }}'s profile</a>
            </div>

            <div class="row mb-4">
                <h1 class="col-sm-10">{{ $user->name }}'s profile</h1>
                <div class="col-sm-2 text-right">
                    @if($user->id != Auth::user()->id)
                        @if($isFollowing)
                            <a href="{{ route('user.follow', $user->id) }}" class="btn btn-outline-primary btn-sm active">Following</a>
                        @else
                            <a href="{{ route('user.follow', $user->id) }}" class="btn btn-outline-primary btn-sm">Follow</a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="row rounded bg-white py-3 mb-3">
                <div class="col-sm-4">
                    <img class="rounded mr-4 shadow-sm" src="{{ asset('storage/'.$user->avatar) }}" alt="avatar" style="width: 100%">
                </div>
                
                <div class="col-sm-8 d-flex flex-column justify-content-between">
                    <div>
                        <div class="row">
                            <div class="col-sm-10">
                                <h4>{{ $user->name }}</h4>
                            </div>
            
                            @if($user->id == Auth::user()->id)
                                <div class="col-sm-2 text-right">
                                    <a href="{{ route('user.edit', Auth::user()->id) }}"><i class="far fa-edit text-muted"></i></a>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="mb-3 text-muted text-break">{{ $user->greeting }}</div>
                            <h6><i class="fas fa-square"></i> Interests</h6>
                            <div class="border-left px-3 mb-3 text-break">{{ $user->interests }}</div>
                        </div>
                    </div>
                    <div>
                        {{-- Links to trigger modal for following/follower user list --}}
                        <span class="text-muted mr-1"><a href="#" data-toggle="modal" data-target="#followingModal">{{ $followings->count() }}</a> Following</span>
                        <span class="text-muted"><a href="#" data-toggle="modal" data-target="#followerModal">{{ $followers->count() }}</a> Followers</span>
                    </div>
                </div>
            </div>

            {{-- Modals for following user list --}}
            <div class="modal fade" id="followingModal" tabindex="-1" role="dialog" aria-labelledby="followingModalTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="followingModalTitle">Following</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @foreach($followings as $following)
                        <div class="row mb-2">
                            <div class="col-sm-9 d-flex align-items-center">
                                <img class="rounded-circle float-left mr-3" src="{{ asset('storage/'.$following->avatar) }}" alt="" style="width: 45px">
                                <div class="float-left"><a href="{{ route('user.show', $following->id) }}" class="text-body">{{ Str::limit($following->name, 40, '...') }}</a></div>
                            </div>
                            <div class="col-sm-3 d-flex align-items-center justify-content-end">
                                {{-- Follow button --}}
                                @if($following->id != Auth::user()->id)
                                    @if($following['auth_is_following'])
                                        <a href="{{ route('user.follow', $following->id) }}" class="btn btn-outline-primary btn-sm active">Following</a>
                                    @else
                                        <a href="{{ route('user.follow', $following->id) }}" class="btn btn-outline-primary btn-sm">Follow</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>

            {{-- Modals for follower user list --}}
            <div class="modal fade" id="followerModal" tabindex="-1" role="dialog" aria-labelledby="followerModalTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="followerModalTitle">Followers</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        @foreach($followers as $follower)
                        <div class="row mb-2">
                            <div class="col-sm-9 d-flex align-items-center">
                                <img class="rounded-circle float-left mr-3" src="{{ asset('storage/'.$follower->avatar) }}" alt="avatar" style="width: 45px">
                                <div class="float-left"><a href="{{ route('user.show', $follower->id) }}" class="text-body">{{ Str::limit($follower->name, 40, '...') }}</a></div>
                            </div>
                            <div class="col-sm-3 d-flex align-items-center justify-content-end">
                                {{-- Follow button --}}
                                @if($follower->id != Auth::user()->id)
                                    @if($follower['auth_is_following'])
                                        <a href="{{ route('user.follow', $follower->id) }}" class="btn btn-outline-primary btn-sm active">Following</a>
                                    @else
                                        <a href="{{ route('user.follow', $follower->id) }}" class="btn btn-outline-primary btn-sm">Follow</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
            </div>

            {{-- Post list of the user in profile page --}}
            <div class="row">
                <h6 class="mb-3">All {{ $user->name }}'s posts : {{ $user->posts->count() }} posts</h6>
                
                @foreach ($posts as $post)
                    <div class="card mb-3 border-0 shadow-sm w-100">
                        <div class="row no-gutters">
                            <div class="col-md-3">
                                <img src="{{ asset('storage/'.$post->post_image) }}" alt="post-image" class="rounded-left w-100 h-100" style="object-fit: cover;">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <h5 class="card-title"><a href="{{ route('post.show', $post->id) }}" class="text-body">{{ $post->title }}</a></h5>
                                    <p class="card-text">{{ Str::limit($post->content, 100, '...') }}</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            @foreach ($post->categories as $category)
                                                <div class="badge badge-pill badge-secondary px-2 py-1">{{ $category->name }}</div>
                                            @endforeach
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <div class="d-inline mr-2">
                                                @if($post->isLiked)
                                                    <i class="fas fa-heart d-inline text-danger"></i>
                                                    <span class="text-danger">{{ $post->likes->count() }}</span>
                                                @else
                                                    <i class="far fa-heart d-inline text-muted"></i>
                                                    <span class="text-muted">{{ $post->likes->count() }}</span>
                                                @endif
                                            </div>
                                            <div class="d-inline text-muted"> 
                                                {{ $post->created_at->diffForHumans() }}
                                                by <a href="{{ route('user.show', $post->user->id) }}" class="text-muted">{{ $post->user->name }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>

@endsection