@extends('admin.layout')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Blank page
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
            <div class="box-header">
              <h3 class="box-title">Листинг сущности</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="form-group">
                <a href="create.html" class="btn btn-success">Добавить</a>
              </div>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Автор</th>
                  <th>Текст</th>
                  <th>Пост</th>
                  <th>Действия</th>
                </tr>
                </thead>
                <tbody>

                @foreach($comments as $comment)
	                <tr>
	                  <td>{{ $comment->id }}</td>
	                  <td>{{ $comment->author->name }}</td>
                    <td>{{ $comment->text }}</td>
	                  <td>{{ $comment->post->title }}</td>
	                  <td>
						@if($comment->status == 1)
	                  		<a href="{{ route('comments.status', $comment->id) }}" class="fa fa-lock"></a>
	                  	@else
	                  		<a href="{{ route('comments.status', $comment->id) }}" class="fa fa-thumbs-o-up"></a>
	                  	@endif

	                  	{{ Form::open(['route' => ['comments.destroy', $comment->id], 'method' => 'delete']) }}
                          <button type="submit" onclick="return confirm('Are you sure?')" class="delete">
                            <i class="fa fa-remove"></i>
                          </button>
                        {{ Form::close() }}

	                  </td>
	                </tr>
	            @endforeach
                
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
@endsection