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
                <a href="{{route('users.create')}}" class="btn btn-success">Добавить</a>
              </div>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Имя</th>
                  <th>E-mail</th>
                  <th>Статус</th>
                  <th>Роль</th>
                  <th>Аватар</th>
                  <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                    <tr>
                      <td>{{$user->id}}</td>
                      <td>{{$user->name}}</td>
                      <td>{{$user->email}}</td>                      
                      <td>{{ $user->getStatus() }}</td>
                      <td>{{ $user->getRole() }}</td>
                      <td>
                        <img src="{{$user->getAvatar()}}" alt="" class="img-responsive" width="100">
                      </td>
                      <td>
                        <a href="{{route('users.edit', $user->id)}}" class="fa fa-pencil" title="Изменить"></a>

                        {{ Form::open(['route' => ['users.destroy', $user->id], 'method' => 'delete']) }}
                            <button type="submit" onclick="return confirm('Are you sure?')" class="delete">
                              <i class="fa fa-remove" title="Удалить"></i>
                            </button>
                        {{ Form::close() }}
                        
                        @if($user->isBanned())
                          <a href="{{ route('users.status', $user->id) }}" class="fa fa-undo" aria-hidden="true" title="Разбанить"></a>
                        @else
                          <a href="{{ route('users.status', $user->id) }}" class="fa fa-ban" aria-hidden="true" title="Забанить"></a>
                        @endif

                        @if($user->isAdmin())
                          <a href="{{ route('users.admin', $user->id) }}" class="fa fa-angle-double-down" aria-hidden="true" title="Сделать обычным пользователем"></a>
                        @else
                          <a href="{{ route('users.admin', $user->id) }}" class="fa fa-angle-double-up" aria-hidden="true" title="Сделать админом"></a>
                        @endif                        
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