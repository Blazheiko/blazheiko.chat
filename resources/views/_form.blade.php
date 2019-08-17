<div class="form-group">
    {{ Form::label ('Заглавие') }}
    {{ Form::text ('title',null,['class' => 'form-control' ]) }}
</div>
<div class="form-group">
    {{ Form::label ('Мысли вслух') }}
    {{ Form::textarea ('post',null,['class' => 'form-control' ]) }}
</div>
<div class="form-group">
    {{ Form::submit ('Save',['class' => 'btn btn-primary' ]) }}
</div>
