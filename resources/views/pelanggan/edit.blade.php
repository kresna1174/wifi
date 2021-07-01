{!! Form::model($model, ['id' => 'form-edit']) !!}
@include('pelanggan.form')
<div class="float-right">    
    <button type="button" class="btn btn-secondary" onclick="bootbox.hideAll()">Cancel</button>
    <button type="button" class="btn btn-primary" onclick="update('<?= $model->id; ?>')">Update</button>
</div>
{!! Form::close() !!}