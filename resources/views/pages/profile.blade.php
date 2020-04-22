@extends('layout')

@section('content')
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <div class="leave-comment mr0"><!--leave comment-->
                    
                    <h3 class="text-uppercase">My profile</h3>
                    <br>

                    <img src="{{ $user->getAvatar() }}" alt="" class="profile-image">

                    @if(Auth::user()->isAdmin())
                        <div class="alert alert-success" align="center">Admin</div>
                    @elseif(Auth::user()->isBanned())
                        <div class="alert alert-danger" align="center">You are banned.</div>
                    @endif

                    @include('admin.errors')

                    {{ Form::open([
                    	'url' => '/profile', 
                    	'class' => 'form-horizontal contact-form',
                    	'files' => true
                    	]) }}
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ $user->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{ $user->email }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="password" class="form-control" id="password" name="password" placeholder="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea name="description" id="" cols="30" rows="3" class="form-control" placeholder="Your status description">{!! $user->description !!}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
								<input type="file" class="form-control" id="image" name="avatar">	
                            </div>
                        </div>
                        <button type="submit" class="btn send-btn">Update</button>
                    {{ Form::close() }}

                </div><!--end leave comment-->
            </div>
            @include('pages.partials._sidebar')
        </div>
    </div>
</div>
@endsection