@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <h1 class="mb-4">Notifications</h1>

            {{-- Notificationが0件の時（既読含む） --}}
            @if ($unread_notifications == NULL && $read_notifications == NULL)

                <div>No notifications yet.</div>

            @else

                {{-- 未読Notification用 --}}
                <div class="mb-3">
                    <h5 class="text-muted"><i class="far fa-square mr-2"></i>Unread</h5>
                    @forelse ($unread_notifications as $unread_notification)
                        <div class="container card px-5 py-3 mb-2 border-0 shadow-sm">
                            <div class="row">
                                <div class="col-md-9">

                                    {{-- notification->type によって表示変更 --}}

                                    {{-- FollowしているUserがPostした時の通知（PostPostedNotification） --}}
                                    @if($unread_notification['type'] === 'App\Notifications\PostPostedNotification')
                                        <span>
                                            <i class="fas fa-newspaper mr-2"></i>
                                            <a href="{{ route('user.show', $unread_notification['user_id']) }}">{{ $unread_notification['user_name'] }}</a>
                                            <span> posted </span>
                                            <a href="{{ route('post.show', $unread_notification['post_id']) }}">{{ $unread_notification['post_title'] }}</a>
                                            <span>[{{ $unread_notification['post_type']}}]</span>
                                        </span>

                                    {{-- 他のUserにFollowされたときの通知 --}}
                                    @elseif($unread_notification['type'] === 'App\Notifications\UserFollowedNotification')
                                        <span>
                                            <i class="fas fa-user mr-2"></i>
                                            <a href="{{ route('user.show', $unread_notification['user_id']) }}">{{ $unread_notification['user_name'] }}</a>
                                            <span> followed you.</span>
                                        </span>
                                    
                                    {{-- 他のUserがEnsembleを作成したときの通知 --}}
                                    @elseif($unread_notification['type'] === 'App\Notifications\EnsembleCreatedNotification')
                                        <span>
                                            <i class="far fa-calendar-alt mr-2"></i>
                                            <a href="{{ route('user.show', $unread_notification['user_id']) }}">{{ $unread_notification['user_name'] }}</a>
                                            <span> created ensemble : </span>
                                            <a href="{{ route('ensemble.show', $unread_notification['ensemble_id']) }}">{{ $unread_notification['ensemble_headline'] }}</a>
                                        </span>
                                    @endif
                                    
                                </div>
                                <div class="col-md-3 text-muted text-right">
                                    {{ $unread_notification['created_at'] }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No unread notification.</div>
                    @endforelse
                </div>

                {{-- 既読Notification用 --}}
                <div class="mb-3">
                    <h5 class="text-muted"><i class="far fa-square mr-2"></i>Read</h5>
                    @forelse ($read_notifications as $read_notification)
                        <div class="container card px-5 py-3 mb-2 border-0">
                            <div class="row">
                                <div class="col-md-9">

                                    {{-- notification->type によって表示変更 --}}

                                    {{-- FollowしているUserがPostした時の通知（PostPostedNotification） --}}
                                    @if($read_notification['type'] === 'App\Notifications\PostPostedNotification')
                                        <span>
                                            <i class="fas fa-newspaper mr-2"></i>
                                            <a href="{{ route('user.show', $read_notification['user_id']) }}">{{ $read_notification['user_name'] }}</a>
                                            <span> posted </span>
                                            <a href="{{ route('post.show', $read_notification['post_id']) }}">{{ $read_notification['post_title'] }}</a>
                                            <span>[{{ $read_notification['post_type']}}]</span>
                                        </span>

                                    {{-- 他のUserにFollowされたときの通知 --}}
                                    @elseif($read_notification['type'] === 'App\Notifications\UserFollowedNotification')
                                        <span>
                                            <i class="fas fa-user mr-2"></i>
                                            <a href="{{ route('user.show', $read_notification['user_id']) }}">{{ $read_notification['user_name'] }}</a>
                                            <span> followed you.</span>
                                        </span>
                                    
                                    {{-- 他のUserがEnsembleを作成したときの通知 --}}
                                    @elseif($read_notification['type'] === 'App\Notifications\EnsembleCreatedNotification')
                                        <span>
                                            <i class="far fa-calendar-alt mr-2"></i>
                                            <a href="{{ route('user.show', $read_notification['user_id']) }}">{{ $read_notification['user_name'] }}</a>
                                            <span> created ensemble : </span>
                                            <a href="{{ route('ensemble.show', $read_notification['ensemble_id']) }}">{{ $read_notification['ensemble_headline'] }}</a>
                                        </span>
                                    @endif

                                </div>
                                <div class="col-md-3 text-muted text-right">
                                    {{ $read_notification['created_at'] }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No read notification.</div>
                    @endforelse
                </div>
            @endif

        </div>
    </div>
</div>

@endsection