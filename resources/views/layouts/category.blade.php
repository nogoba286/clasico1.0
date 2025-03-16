<div class="custom-dialog category-nav">
    <button class="btn btn-outline-primary" onclick="goToHome()">
        <i class="fa fa-home"></i>
    </button>
    <button class="btn btn-outline-primary" onclick="fetchonlylivebets()">
        @include('components.liveicon')
    </button>
    <button class="btn btn-outline-primary" onclick="fetchMyBets()">
        <span style="font-size: 10px;">My Bets</span>
    </button>
</div>
<div class="position-relative custom-dialog">
    <!-- Dropdown Button -->
    <button id="btn_favourite" type="button" class="btn btn-outline-primary custom-dropdown-button">
      <i class="custom-icon-title bi bi-star"> favourite</i>
      <i class="bi bi-chevron-bar-expand custom-dropdown-icon"></i>    
    </button>
    <!-- Full Height Dropdown List -->
    <ul class="custom-dropdown-menu favourite-dropdown w-100 show">
        @foreach($data['favouriteBets'] as $favourite)
        <li>
            <a class="dropdown-item" onclick="fetchMatchDetails({{ $favourite['event_id'] }})" href="#">
                <span>{{$favourite['date']}} &nbsp{{ $favourite['home_team'] }} vs {{ $favourite['away_team'] }}</span>
            </a>
        </li>
        @endforeach
    </ul>
</div>

<div class="position-relative custom-dialog">
    <!-- Dropdown Button -->
    <button id="btn_top_league" type="button" class="btn btn-outline-primary custom-dropdown-button">
      <i class="custom-icon-title bi bi-trophy"> Top League</i>
      <i class="bi bi-chevron-bar-expand custom-dropdown-icon"></i>    
    </button>

    <!-- Full Height Dropdown List -->
    @if(isset($data['fetchTopLeagues']) && count($data['fetchTopLeagues']) > 0)
    <ul class="custom-dropdown-menu w-100 show">
        @foreach($data['fetchTopLeagues'] as $league)
        <li>
            <a class="dropdown-item" href="#">
                <img src="{{ $league['logo'] }}" alt="{{ $league['name'] }}" class="league-logo">
                <span>&nbsp{{ $league['name'] }}</span>
            </a>
        </li>
        @endforeach
    </ul>
    @else
    <p class="text-muted mt-2">No top leagues available.</p>
    @endif
</div>
<div class="custom-searchbar">
    <i class="fa fa-search"></i>&nbsp
    <input type="text" placeholder="Enter Team or Champion nam" />
</div>
