@extends('app')

@section('content')
    @include('layouts.slide')
    <input type="text" id="token" value="{{csrf_token()}}" hidden>
    <div class="content">
        <div class="category">
            @include('layouts.category')
        </div>
        <div class="content-container">
            <div class="home">
                <div class="weekdays">
                    @foreach($data['weekdays'] as $key=>$weekday)
                        <button class="btn btn-weekday" onclick="fetchAllDataByDate('{{$key}}')">
                            {{ $weekday['day'] }} <strong>{{ $weekday['date'] }}</strong>
                            <input type="text" value="{{$weekday['full-date']}}" hidden/>
                        </button>
                    @endforeach
                </div>
                <div class="swiper-container home-child" id="sportsCarousel">
                    <div class="swiper-wrapper">
                        @foreach ($data['sports'] as $sport)
                            <div class="swiper-slide sport-swiper-item">
                                <div class="btn btn-light sport-type-button">
                                    <div style="background-image: url({{ $sport['icon'] }}); background-size: cover; background-position: center; width: 40px; height: 40px;">
                                        <!-- Icon -->
                                    </div>
                                    <span style="display: block; font-size: smaller; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $sport['name'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="event-carousel-container home-child">
                    <div class="swiper-container" id="eventCarousel">
                        <div class="swiper-wrapper">
                            @foreach($data['liveAllEvents'] as $event)
                                <div class="swiper-slide live-item" data = "{{$event['fixture']['id']}}">
                                    <div class="custom-event-item custom-dialog">
                                        <input type="text" value="{{$event['fixture']['id']}}" hidden/>
                                        <div class="d-flex align-items-center gap-2">
                                            <span>{{ date('H:i m/d', strtotime($event['fixture']['date'])) }}</span>
                                            <img style="width:6%;" src="{{ $event['league']['logo'] }}" alt="{{ $event['league']['name'] }}">
                                            <span>{{$event['league']['name']}}</span>
                                        </div>
                                        <div class="teams">
                                            <div class="teams-name">
                                                <span>
                                                    {{$event['teams']['home']['name']}}
                                                </span>
                                                <span>
                                                    {{$event['teams']['away']['name']}}
                                                </span>
                                            </div>
                                            <div>
                                                @include('components.liveicon')
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="live-now home-child">
                    <div class="custom-long-dialog">
                        <div>
                            <span class="dialog-title">Live Now</span>
                        </div>
                        <div>
                            <ul class="nav nav-pills overview" role="tablist">
                                @foreach($data['sports'] as $sport)
                                <li class="nav-item">
                                    <div class="sport-icon" style="background-image: url({{ $sport['icon'] }});"></div>
                                    <a class="nav-link" data-bs-toggle="pill" href="#home">{{$sport['name']}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="live-now-content home-child">
                    @foreach($data['liveAllEvents'] as $event)
                        <div class="custom-long-dialog custom-event-long-item live-item" data = "{{$event['fixture']['id']}}">
                            <a onclick="addFavorite('{{$event['fixture']['id']}}', '{{$event['teams']['home']['name']}}', '{{$event['teams']['away']['name']}}', '{{$event['fixture']['date']}}')"><i class="far fa-star"></i></a>
                            <div class="event-info">
                                <div class="live-staus">
                                    <div>
                                        @include('components.liveicon')
                                    </div>
                                </div>
                                <div class="home-team">
                                    <span>{{$event['teams']['home']['name']}}</span>
                                    <span>{{$event['goals']['home']}}</span>
                                </div>
                                <div class="away-team">
                                    <span>{{$event['teams']['away']['name']}}</span>
                                    <span>{{$event['goals']['away']}}</span>
                                </div>
                            </div>
                            <div class="betting-info">
                                <div class="league-info">
                                    <img style="width:2%;" src="{{ $event['league']['logo'] }}" alt="{{ $event['league']['name'] }}">
                                    <span>{{$event['league']['name']}}</span>
                                </div>
                                <div class="betting-buttons">

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="live-now mt-3 home-child">
                    <div class="custom-long-dialog">
                        <div>
                            <span class="dialog-title">Upcoming</span>
                        </div>
                        <div>
                            <ul class="nav nav-pills overview" role="tablist">
                                @foreach($data['sports'] as $sport)
                                <li class="nav-item">
                                    <div class="sport-icon" style="background-image: url({{ $sport['icon'] }});"></div>
                                    <a class="nav-link" data-bs-toggle="pill" href="#home">{{$sport['name']}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="upcoming-content home-child">
                    @foreach($data['upcomingAllEvents'] as $event)
                        <div class="custom-long-dialog custom-event-long-item" data = "{{$event['fixture']['id']}}">
                            <a onclick="addFavorite('{{$event['fixture']['id']}}', '{{$event['teams']['home']['name']}}', '{{$event['teams']['away']['name']}}', '{{$event['fixture']['date']}}')"><i class="far fa-star"></i></a>
                            <div class="event-info">
                                <div class="live-staus">
                                </div>
                                <div class="home-team">
                                    <span>{{$event['teams']['home']['name']}}</span>
                                    <span>{{$event['goals']['home']}}</span>
                                </div>
                                <div class="away-team">
                                    <span>{{$event['teams']['away']['name']}}</span>
                                    <span>{{$event['goals']['away']}}</span>
                                </div>
                            </div>
                            <div class="betting-info">
                                <div class="league-info">
                                    <img style="width:2%;" src="{{ $event['league']['logo'] }}" alt="{{ $event['league']['name'] }}">
                                    <span>{{$event['league']['name']}}</span>
                                </div>
                                <div class="betting-buttons">

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="odds-detail">
                <div class="category-back-buttons">
                    
                </div>
                <div class="custom-long-dialog match-info">

                </div>
                <input id="betItemId" type="text" hidden>                    
                <div class="bet-details">

                </div>
            </div>
            <div class="event_list">

            </div>
            <div class="my-bets">

            </div>
        </div>
        <div class="tools">
            @include('layouts.tools')
        </div>
    </div>
    
    <script src="{{asset('assets/js/sportsbetting.js')}}"></script>
@endsection
