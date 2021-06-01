{!! Form::model($model, ['id' => $model]) !!}
@include('pelanggan.form')
<div class="float-right">    
    <button type="button" class="btn btn-secondary" onclick="bootbox.hideAll()">Cancel</button>
    <button type="button" class="btn btn-primary" onclick="update()">update</button>
</div>
{!! Form::close() !!}