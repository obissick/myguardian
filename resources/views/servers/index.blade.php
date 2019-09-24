@extends('layouts.app')

@section('content')

<div class="container container-fluid">
    @include('partials.flash')
    <h2>DB Servers</h2>
    <button id="btn-add" name="btn-add" class="btn btn-success btn-xs" data-toggle="modal" data-target="#server">Add Server</button>
    <div class="row">
        <div class="table-responsive-sm">
            <br />
            <!-- Table-to-load-the-data Part -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>IP Address</th>
                        <th>Port</th>
                        <th>Username</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody id="servers-list" name="servers-list">
                    @foreach ($servers as $server)
                        <tr>
                            <td class="table-text">
                                <div><a href="{{route('servers.show', $server->id)}}">{{ $server->name }}</a></div>
                            </td>

                            <td>
                                {{ $server->ip }}
                            </td>
                                
                            <td>
                                {{ $server->port }}
                            </td>

                            <td>
                                {{ $server->username }}
                            </td>

                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <button class="btn btn-info btn-xs btn-detail edit-server" value="{{$server->id}}" data-toggle="modal" data-target="#server">Edit</button>
                                    <button class="btn btn-danger btn-xs btn-delete delete-server" value="{{$server->id}}">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
                <!-- End of Table-to-load-the-data Part -->
                <!-- Modal (Pop up when detail button clicked) -->
                <div class="modal fade" id="server" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">Server Editor</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form id="addserver" name="addserver" class="form-horizontal" method="POST" action="{{ route('servers.store') }}" novalidate="">
                                    {{ csrf_field() }}
                                    
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="address" class="col-sm-3 control-label">IP Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="address" name="address" placeholder="0.0.0.0" value="">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="port" class="col-sm-3 control-label">Port</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="port" name="port" placeholder="3306" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="username" class="col-sm-3 control-label">Username</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="username" name="username" placeholder="" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="" value="">
                                        </div>
                                    </div>
                                    
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                                        <input type="hidden" id="server_id" name="server_id" value="0">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <script
            src="https://code.jquery.com/jquery-3.4.1.js"
            integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
            crossorigin="anonymous">
        </script>
        <script>
            $('.table').on('click', '.edit-server', function(){
                var name = $(this).data('name');
                var ip = $(this).data('address');
                var port = $(this).data('port');
                var username = $(this).data('username');

                $(".address").val(ip);
                $(".name").val(name);
                $(".port").val(port);
                $(".username").val(username);   
                $('#server').modal('show');
            });
        </script>
</div>

@endsection