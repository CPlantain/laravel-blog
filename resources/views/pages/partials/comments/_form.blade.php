
	<div class="leave-comment"><!--leave comment-->
	    <h4>Leave a reply</h4>

	    @include('admin.errors')
	
	    {{ Form::open(['url' => '/comment', 'class' => 'form-horizontal contact-form']) }}
	    	<input type="hidden" name="parent_id" value="{{ $parent_id }}">	    
	    	<input type="hidden" name="post_id" value="{{ $post->id }}">
	        <div class="form-group">
	            <div class="col-md-12">
					<textarea class="form-control" rows="4" name="text" placeholder="Write Massage"></textarea>
	            </div>
	        </div>
	        <button class="btn send-btn">Post Comment</button>
	    {{ Form::close() }}
	</div><!--end leave comment--> 
@if(Auth::user()->isBanned())
	<div class="leave-comment">
		<div class="alert alert-danger mt-5">You cannot leave comments.</div>
	</div>
@endif