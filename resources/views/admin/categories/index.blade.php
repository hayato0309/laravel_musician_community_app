@extends('layouts.admin')

@section('admin.content')
    @if(session('category-created-message'))
        <div class="alert alert-success">{{ session('category-created-message') }}</div>
    @elseif(session('category-updated-message'))
        <div class="alert alert-success">{{ session('category-updated-message') }}</div>
    @elseif(session('category-deleted-message'))
        <div class="alert alert-danger">{{ session('category-deleted-message') }}</div>
    @endif

    <h1 class="mb-4">Category list</h1>
    
    <div class="row mb-4">
        <div class="col-sm-5">
            <form action="{{ route('admin.categoryStore') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" name="name" class="form-control {{$errors->has('name')?'is-invalid':''}}" placeholder="Category name">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
                @if($errors->has('name'))
                    <p class="text-danger">{{ $errors->first('name') }}</p>
                @endif
            </form>
        </div>
    
        <div class="col-sm-5">
            <form action="{{ route('admin.categories') }}" method="GET">
                @csrf
                <div class="input-group">
                    <input type="text" name="category_search" class="form-control" value="{{ isset($category_search) ? $category_search : '' }}" placeholder="Search keyword">
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-sm-2">
            <a href="{{ route('admin.categories') }}" class="btn btn-outline-dark">Clear the keyword</a>
        </div>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Posts</th>
                <th scope="col">Created at</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->posts->count() }}</td>
                    <td>{{ $category->created_at }}</td>
                    <td>
                        <!-- Button trigger modal for updating category -->
                        <button type="button" class="btn btn-link p-0" data-toggle="modal" data-target="#modal-update-{{ $category->id }}">
                            <i class="far fa-edit text-body"></i>
                        </button>

                        <!-- Modal for updating category -->
                        <div class="modal fade" id="modal-update-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.categoryUpdate', $category->id) }}" method="POST">
                                        <div class="modal-body">
                                            @csrf
                                            @method('PATCH')
    
                                            <input type="text" class="form-control" name="name" value="{{ $category->name }}">
                                        </div> 
            
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>   
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <!-- Button trigger modal for deleting category -->
                        <button type="button" class="btn btn-link p-0" data-toggle="modal" data-target="#modal-delete-{{ $category->id }}">
                            <i class="far fa-trash-alt text-body"></i>
                        </button>

                        <!-- Modal for deleting category -->
                        <div class="modal fade" id="modal-delete-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-2">Are you sure you want to delete this category?</div>
                                        <div class="mb-2 px-3 border-left">{{ $category->name }}</div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <form action="{{ route('admin.categoryDestroy', $category->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger-custamized">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>   
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
@endsection