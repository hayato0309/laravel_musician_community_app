@extends('layouts.admin')

@section('admin.content')
    <div class="container">
        <div class="mb-5">
            <div class="mb-3">
                <h3>Total number</h3>
            </div>
            <div class="d-flex flex-row">
                <h5 class="mr-3">Users</h5 class="mr-3">
                <h5 class="mr-3">{{ $num_of_total_users }}</h5 class="mr-3">
                <h5 class="mr-3">Posts</h5 class="mr-3">
                <h5 class="mr-3">{{ $num_of_total_posts }}</h5 class="mr-3">
            </div>
        </div>
        <div class="mb-5">
            <div class="mb-3">
                <h3>By period</h3>
            </div>
            <table class="table">
                <tr>
                    <th></th>
                    <th colspan="2">Day</th>
                    <th colspan="2">Week</th>
                    <th colspan="2">Month</th>
                    <th colspan="2">Year</th>
                </tr>
                <tr>
                    <td>Users</td>
                    @foreach($num_of_users_per_period as $num_of_users)
                        <td>{{ $num_of_users }}</td>
                        <td>+10%</td>
                    @endforeach
                </tr>
                <tr>
                    <td>Posts</td>
                    @foreach($num_of_posts_per_period as $num_of_posts)
                        <td>{{ $num_of_posts }}</td>
                        <td>+10%</td>
                    @endforeach
                </tr>
            </table>
        </div>
        <div>
            <h3 class="mb-3">Ranking</h3>
            <div class="card-deck">
                <div class="card border-0 shadow-sm px-3">
                    <div class="card-body text-center">
                        <h5>Popular users Top5</h5>
                        <div class="small text-muted mb-3">*Based on the number of followers</div>

                        @foreach($popular_users_top5 as $popular_user)
                            <div class="row d-flex justify-content-between mb-2">
                                <div>
                                    <span class="mr-2">{{ $loop->iteration }}</span>
                                    <img class="rounded-circle" src="{{ asset('storage/'.$popular_user->avatar) }}" alt="avatar" style="width: 25px;">
                                </div>
                                
                                <div>{{ $popular_user->name }}</div>
                                <div>{{ $popular_user->followers_count }} <span class="small"> followers</span></div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Contributers Top5</h5>
                        <div class="small text-muted">*Based on the number of posts</div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Popular posts Top5</h5>
                        <div class="small text-muted">*Based on the number of likes</div>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h5>Ensembles Top5</h5>
                        <div class="small text-muted">*Based on the number of participants</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection