@extends('layout')

@section('content')
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <div class="leave-comment mr0"><!--leave comment-->
                    
                    <h3 class="text-uppercase">Login</h3>
                    <br>
                    @include('admin.errors')

                    {{ Form::open(['url' => '/login', 'class' => 'form-horizontal contact-form']) }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                            </div>
                        </div>
                        <button type="submit" class="btn send-btn">Login</button>

                    {{ Form::close() }}
                </div><!--end leave comment-->
            </div>
            @include('pages.partials._sidebar')
        </div>
    </div>
</div>
@endsection