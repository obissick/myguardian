@extends('layouts.app')

@section('content')

<div class="container container-fluid">
    @include('partials.flash')
    <h2>DB Users</h2>
    <div class="row">
        <div class="table-responsive-sm">
            <br />
            <table class="table table-responsive display" id="users">
                <thead>
                    <tr>
                        <th>Server</th>
                        <th>User</th>
                        <th>Host</th>
                        <th>Expire Date</th>
                        <th>Expired</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="users-list" name="users-list">
                    @foreach ($users as $user)
                        <tr>
                            <td class="table-text">
                                <div>{{ $user->name }}</div>
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
                                {{ $user->expired }}
                            </td>

                            <td>
                                <button class="btn btn-info btn-xs btn-detail edit-user" value="{{$user->id}}" data-toggle="modal" data-target="#edit">Set Expire</button> 
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Server</th>
                        <th>User</th>
                        <th>Host</th>
                        <th>Access Expire</th>
                        <th>Expired</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
            
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
                <form id="updateuser" name="updateuser" class="form-horizontal" method="POST" action="{{ route('users.update', '') }}" novalidate="">
                <div class="modal-body">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Expire Date/Time</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="time" name="time" placeholder="" value="">
                            <input type="hidden" id="user_id" name="user_id" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="id" value="{{$user->id}}">Save changes</button>
                </div>
            </form>
            </div>
            </div>
        </div>
</div>

<script>
    $(document).ready(function() {
        // Setup - add a text input to each footer cell
        $('#users tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
        } );
    
        // DataTable
        var table = $('#users').DataTable();
    
        // Apply the search
        table.columns().every( function () {
            var that = this;
    
            $( 'input', this.footer() ).on( 'keyup change clear', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            } );
        } );
    } );
</script>
<script>
    $(document).on("click", ".edit-user", function () {
        var id = $(this).val();
        var users = "users/";
        var link = users.concat(id);
        $("#updateuser").attr('action', link); 
        $(".modal-body #id").val($(this).data('id')); 
    });
</script>

@endsection