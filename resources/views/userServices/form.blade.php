<div class="form-group">
    <label>Username</label>
    {!! Form::text('name', null, ['class' => 'form-control', 'id' => "name"]) !!}
</div>
<div class="form-group">
    <label>Password</label>
    {!! Form::password('password', ['class' => 'form-control', 'id' => "password"]) !!}
</div>
<div class="form-group">
    <label>Konfirmasi Password</label>
    {!! Form::password('konfirmasi_password', ['class' => 'form-control', 'id' => "konfirmasi_password"]) !!}
</div>
