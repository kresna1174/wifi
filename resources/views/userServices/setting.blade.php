@extends('layout.main')
@section('content')
<style>

    .sidebar-bg {
        display: none;
    }

    .sidebar {
        display: none;
    }

    .header {
        display: none;
    }

    body {
        background: #fff;
        margin: 0;
        padding: 0;
    }
</style>


<div class="container">
<?php if(session('error')) { ?>
        <div class="alert alert-danger error">
            {{session('error')}}
        </div>
    <?php } ?>
    <form action="{!! route('UserService.change-password', $model->id) !!}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
            <h4>Username</h4>
            <input type="text" name="name" class="form-control form-control-lg" placeholder="Username" required />
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-lg">Save</button>
        </div>
    </form>
        <hr>
        {!! Form::open(['route' => ['UserService.change-password', $model->id]]) !!}
        {{ csrf_field() }}
        <div class="form-group">
            <h4>Password Lama</h4>
            <input type="password" name="old_password" class="form-control form-control-lg" placeholder="Old Password" required />
        </div>
        <div class="form-group">
            <h4>Password Baru</h4>
            <input type="password" name="new_password" class="form-control form-control-lg" placeholder="New Password" required />
        </div>
        <div class="form-group">
            <h4>Konfirmasi Password</h4>
            <input type="password" name="konfirmasi_password" class="form-control form-control-lg" placeholder="Konfirmasi Password" required />
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-lg">Save</button>
        </div>
        {!! Form::close() !!}
    </div>

@endsection

@section('script')
    <script>
        $(function() {
        })
    </script>
@endsection