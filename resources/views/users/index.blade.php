@extends('layouts.app')

@section('content')

<div class="container container-fluid">
    @include('partials.flash')
    <h2>DB Users</h2>
    <div class="row">
        <div class="table-responsive-sm">
            <br />
            <!-- Table-to-load-the-data Part -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Server</th>
                        <th>User</th>
                        <th>Host</th>
                        <th>Access Expire</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody id="users-list" name="users-list">
                    @foreach ($users as $user)
                        <tr>
                            <td class="table-text">
                                <div><a href="{{route('users.show', $user->id)}}">{{ $user->name }}</a></div>
                            </td>

                            <td>
                                {{ $user->user }}
                            </td>
                                
                            <td>
                                {{ $user->host }}
                            </td>

                            <td>
                                {{ $user->expire }}
                            </td>

                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <button class="btn btn-info btn-xs btn-detail edit-server" value="{{$user->id}}" data-toggle="modal" data-target="#edit">Edit</button>
                                    <button class="btn btn-danger btn-xs btn-delete delete-server" value="{{$user->id}}">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
        <!-- Modal -->
        <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Expire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <form id="addserver" name="addserver" class="form-horizontal" method="POST" action="{{ route('users.update') }}" novalidate="">
                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" placeholder="" value="">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </div>
        </div>
</div>

@endsection