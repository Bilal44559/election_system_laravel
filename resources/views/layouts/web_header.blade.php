<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="{{ route('index') }}">Election System</a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item active">
            <a class="nav-link" href="{{ route('index') }}">Home <span class="sr-only">(current)</span></a>
          </li>
          @if(count(showAllElections()) > 0)
          @foreach(showAllElections() as $election)
          <li class="nav-item">
            <a class="nav-link" href="{{ route('election_page', $election->slug) }}">{{ $election->title }}</a>
          </li>
          @endforeach
          @endif
        </ul>
      </div>

</nav>
