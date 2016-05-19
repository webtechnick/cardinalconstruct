@if ($signedIn && $user->isAdmin())
    <div class="dropdown admin-box pull-left">
      <span class="glyphicon-lg hand glyphicon glyphicon-cog" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></span>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li>
            <a href="/gallery/{{$gallery->slug}}/edit"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
        </li>
        <li>
            <a href="#" onclick="$('#deleteGallery{{ $gallery->id }}').submit();">
                <span class="glyphicon glyphicon-trash"></span> Delete
            </a>
            <form id="deleteGallery{{ $gallery->id }}" method="POST" action="/gallery/{{ $gallery->slug }}">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
            </form>
        </li>

      </ul>
    </div>
@endif