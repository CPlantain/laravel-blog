@extends('admin.layout')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Написать письмо
        <small>приятные слова..</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Новая рассылка</h3>

          @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
          @endif
        </div>
        {{ Form::open(['route' => 'mailings.send']) }}
        <div class="box-body">
          
          <div class="col-md-12">
            <div class="form-group">
              <label for="exampleInputEmail1">Основной текст</label>
              <textarea name="mail_body" id="" cols="30" rows="20" class="form-control">{{old('mail_body')}}</textarea>
          </div>

        </div>
      </div>
        <!-- /.box-body -->
        <div class="box-footer">          
          <button class="btn btn-success pull-right">Отправить</button>
        </div>
        {{ Form::close() }}
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
@endsection