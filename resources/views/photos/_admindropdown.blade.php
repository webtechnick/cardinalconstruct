@if ($signedIn && $user->isAdmin())
    <div class="dropdown admin-box">
      <span class="glyphicon-lg hand glyphicon glyphicon-cog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></span>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li>
            @if ($photo->isActive())
                <a href="/photos/{{$photo->id}}/toggle"><span class="glyphicon glyphicon-remove"></span> Unapprove</a>
            @else
                <a href="/photos/{{$photo->id}}/toggle"><span class="glyphicon glyphicon-ok"></span> Approve</a>
            @endif
        </li>
        <li>
            <a href="/photos/{{$photo->id}}/edit"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
        </li>
        <li>
            <a href="#" onclick="$('#deletePhoto{{$photo->id}}').submit();"><span class="glyphicon glyphicon-trash"></span> Delete</a>
            <form id="deletePhoto{{$photo->id}}" method="POST" action="/photos/{{ $photo->id }}">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
            </form>
        </li>

      </ul>
    </div>
@endif