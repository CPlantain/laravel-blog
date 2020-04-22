<div class="bottom-comment" style="border-left: solid 2px #d4d4d4;">
    <div class="comment-img"  style="padding-bottom: 10px">
        <img class="img-circle" src="{{ $comment->author->getAvatar() }}" alt="" width="105px" height="105px">

    </div>

    <div class="comment-text ">
    	
        <h5>{{ $comment->author->name }}</h5>

        <p class="comment-date">
            {{ $comment->created_at->diffForHumans() }}
        </p>

        <p class="para">{!! $comment->text !!}</p>

        @if($comment->parent)
        	<p style="color: #c9c9c9;">to {{ $comment->parent->author->name }}</p>
        @endif

        @if(Auth::check())
        	@if(!Auth::user()->isBanned())
        	<a class="replay btn pull-right" role="button" onclick="display(document.getElementById('commentForm{{$comment->id}}'))"> Reply</a>

        	<div id="commentForm{{$comment->id}}" style="display: none;">
        		@include('pages.partials.comments._form', 
        			['parent_id' => $comment->id])
        	</div>
			@endif
        @endif
        
        @if($comment->subComments)
        	<div style="padding-left: 50px;">
        		@include('pages.partials.comments._list', 
				['comments' => $comment->subComments()->where('status', 1)->get()])
        	</div>	
        @endif
    </div>
</div>

<script type="text/javascript">
	function display(elem) {
		if (elem.style.display == "none") {
			elem.style.display = "block";
		} else {
			elem.style.display = "none";
		}
	}
</script>