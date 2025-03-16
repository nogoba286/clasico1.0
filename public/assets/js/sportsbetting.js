document.addEventListener("DOMContentLoaded", function () {
    let liveData;
    // Fallback: Fetch odds every 5 seconds
    // function fetchLiveOdds() {
    //     fetch('/update-live')
    //         .then(response => response.json())
    //         .then(data => {
    //             liveData = data.data;
    //             updateData(liveData);
    //         })
    //         .catch(error => console.error("Error fetching live odds:", error));
    // }
    // setInterval(fetchLiveOdds, 5000);
});

function goToHome(){
    window.location.href = '/';
}

function updateData(data){
    updateWeekday(data['weekdays']);
    updateLiveOdds(data['liveAllEvents']);
    updateUpcomingOdds(data['upcomingAllEvents']);
}

function updateWeekday(weekdays){
    let str = "";
    weekdays.forEach(element => {
        str+=`<button class="btn btn-weekday">${element["day"]}<strong>${element["date"]}</strong></button>`;
    });
    $('.weekdays').html(str);
}

function updateLiveOdds(odds){
    let str = "";

    odds.forEach(element => {
        str += `<div class="custom-long-dialog custom-event-long-item" data="${element['fixture']['id']}">`;
        str += `<a onclick="addFavorite(${element['fixture']['id']}, ${element['teams']['home']['name']}, ${element['teams']['away']['name']}, ${element['fixture']['date']})"><i class="far fa-star"></i></a>`;
        str += `<div class="event-info">`;
        str += `<div class="live-staus"></div>`;
        str += `<div class="home-team">`;
        str += `<span>${element['teams']['home']['name']}</span>`;
        str += `<span>${!element['goals']['home'] ? 0 : element['goals']['home']}</span>`;
        str += `</div>`;
        str += `<div class="away-team">`;
        str += `<span>${element['teams']['away']['name']}</span>`;
        str += `<span>${!element['goals']['away'] ? 0 : element['goals']['away']}</span>`;
        str += `</div></div>`;
        str += `<div class="betting-info">`;
        str += `<div class="league-info">`;
        str += `<img style="width:2%;" src="${ element['league']['logo'] }" alt="${ element['league']['name'] }">`;
        str += `<span>${element['league']['name']}</span>`;
        str += `</div></div></div>`;

    });
    $('.live-now-content').html(str);
}

function addFavorite(fixtureId, homeTeam, awayTeam, date){
    $.ajax({
        url: '/add-favorite',
        method: 'post',
        data: {
            _token: $("#token").val(),
            fixtureId: fixtureId,
            homeTeam: homeTeam,
            awayTeam: awayTeam,
            date: date
        },
        success: function(response) {
            showToast(response.message);
            let str = "";
            response.favouriteBets.forEach(element => {
                str += `<li><a class="dropdown-item" href="#" onclick="fetchMatchDetails(${element['event_id']})"><span>${element['date']}</span><span>${element['home_team']} vs ${element['away_team']}</span></a></li>`;
            });
            $('.favourite-dropdown').html(str);
        },
        error: function(error) {
            if (error.status === 403) {
                showToast("You have to login.");
            } else {
                showToast("An error occurred. Please try again.");
            }
        }
    });
}

function updateUpcomingOdds(odds){
    let str = "";

    odds.forEach(element => {
        str += `<div class="custom-long-dialog custom-event-long-item" data="${element['fixture']['id']}">`;
        str += `<a onclick="addFavorite(${element['fixture']['id']}, ${element['teams']['home']['name']}, ${element['teams']['away']['name']}, ${element['fixture']['date']})"><i class="far fa-star"></i></a>`;
        str += `<div class="event-info">`;
        str += `<div class="live-staus"></div>`;
        str += `<div class="home-team">`;
        str += `<span>${element['teams']['home']['name']}</span>`;
        str += `<span>${!element['goals']['home'] ? 0 : element['goals']['home']}</span>`;
        str += `</div>`;
        str += `<div class="away-team">`;
        str += `<span>${element['teams']['away']['name']}</span>`;
        str += `<span>${!element['goals']['away'] ? 0 : element['goals']['away']}</span>`;
        str += `</div></div>`;
        str += `<div class="betting-info">`;
        str += `<div class="league-info">`;
        str += `<img style="width:2%;" src="${element['league']['logo'] }" alt="${ element['league']['name'] }">`;
        str += `<span>${element['league']['name']}</span>`;
        str += `</div></div></div></div>`;

    });
    $('.upcoming-content').html(str);
}


function fetchAllDataByDate(date){
    $("#loadingModal").fadeIn();
    if(date == 0){
        window.location.href = '/';
    }else{
        $.ajax({
            url: '/get-all-data-by-date',
                method: 'GET',
                data:{
                    date: date
                },
                success: function(response) {
                    $("#loadingModal").fadeOut();
                    let data = response['data'];
                    updateEventList(data);
                },
                error: function(error) {
                    $("#loadingModal").fadeOut();    
                }
        });
    }
}

function updateEventList(odds){

    $('.event_list').show();
    $('.home-child').hide();
    $('.home').show();
    $('.odds-detail').hide();
    $('.my-bets').hide();

    let str = "";

    odds.forEach(element => {
        str += `<div class="custom-long-dialog custom-event-long-item" data="${element['fixture']['id']}">`;
        str += `<div class="event-info">`;
        str += `<div class="live-staus">`;
        str += `<a><i class="far fa-star"></i></a></div>`;
        str += `<div class="home-team">`;
        str += `<span>${element['teams']['home']['name']}</span>`;
        str += `<span>${!element['goals']['home'] ? 0 : element['goals']['home']}</span>`;
        str += `</div>`;
        str += `<div class="away-team">`;
        str += `<span>${element['teams']['away']['name']}</span>`;
        str += `<span>${!element['goals']['away'] ? 0 : element['goals']['away']}</span>`;
        str += `</div></div>`;
        str += `<div class="betting-info">`;
        str += `<div class="league-info">`;
        str += `<img style="width:2%;" src="${element['league']['logo'] }" alt="${ element['league']['name'] }">`;
        str += `<span>${element['league']['name']}</span>`;
        str += `</div></div></div></div>`;

    });
    $('.event_list').html(str);
}

function fetchMyBets(){
    $.ajax({
        url: '/fetch-my-bets',
        method: 'post',
        data: {
            _token: $("#token").val()
        },
        success: function(response) {
            let str = "";
            $('.event_list').hide();
            $('.home').hide();
            $('.odds-detail').hide();
            $('.my-bets').show();
            response.bets.forEach(element => {
                str += `
            <div class="betted-item custom-dialog" data="${element['id']}">
                <div class="event-info">
                    <div>
                        <i class="fa fa-football"></i>&nbsp;<span>Europe Champion</span>
                    </div>
                </div>
                <div class="betting-name">
                    <span>${!element['home_team'] ? "" : element['home_team']} vs ${!element['away_team'] ? "" : element["away_team"]}</span>
                </div>
                <div class="betted-info">
                    <span>${element['bet_type']}</span>
                    <span>${element['bet_value']}</span>
                </div>
                <div class="betted-info">
                    <span>${element['stake']}</span>
                    <span>${(element['stake'] * element['odds']).toFixed(2)}</span>
                </div>
            </div>
        `;
            });
            $('.my-bets').html(str);
        },
        error: function(error) {
            console.log(error);
        }
    });
}

$(document).on('click', '.custom-long-dialog .event-info', function() {
    // Get the "data" attribute value (which is the fixture ID)
    let fixtureId = $(this).parent().attr('data');
    
    // Now you can use the fixture ID
    // alert("Fixture ID: " + fixtureId);
    fetchMatchDetails(fixtureId);

});

$(document).on('click', '.live-item', function() {
    // Get the "data" attribute value (which is the fixture ID)
    let fixtureId = $(this).attr('data');
    
    // Now you can use the fixture ID
    // alert("Fixture ID: " + fixtureId);
    fetchMatchDetails(fixtureId);

});


$(document).on('click', '.live-item .event-info', function() {
    // Get the "data" attribute value (which is the fixture ID)
    let fixtureId = $(this).parent().attr('data');
    
    // Now you can use the fixture ID
    // alert("Fixture ID: " + fixtureId);
    fetchMatchDetails(fixtureId);

});

// Function to call match details (Optional)
function fetchMatchDetails(fixtureId) {
    $("#loadingModal").fadeIn();
    $.ajax({
        url: '/get-odd-details',
        method: 'GET',
        data:{
            fixtureId: fixtureId
        },
        success: function(response) {
            let data = response['data'];
            $('#betItemId').val(fixtureId);
            $('.odds-detail').show();
            $('.home').hide();
            $('.home-child').hide();
            setOddsDetail(data);
            $("#loadingModal").fadeOut();
        },
        error: function(error) {
            $("#loadingModal").fadeOut();
        }
    });
}

function setOddsDetail(data){
    let subCategoryStr = "";
    subCategoryStr += `<button class="btn btn-secondary" onclick="backToCategory()"><i class="fa fa-arrow-alt-circle-left"></i></button>`;
    subCategoryStr += `<button class="btn btn-secondary" onclick="backToCategory()">Football</button>`;
    subCategoryStr += `<button class="btn btn-secondary" onclick="getEventByLeague(${data["league"]["id"]})">${data["league"]["name"]}</button>`;
    subCategoryStr += `<button class="btn btn-secondary">${data["teams"]["home"]["name"]} vs ${data["teams"]["away"]["name"]}</button>`;
    $('.category-back-buttons').html(subCategoryStr);

    let str_status = ""
    str_status += `<div class="home-team"><img src="${data["teams"]["home"]["logo"]}" alt=""><span>${data["teams"]["home"]["name"]}</span></div>`
    str_status += `<div class="goal-info"><span class="time">${data["status"]["long"]}</span><span class="goals">${ !data["goals"]["Home"] ? 0 : data["goals"]["Home"] }:${ !data["goals"]["away"] ? 0 : data["goals"]["away"] }</span></div>`
    str_status += `<div class="away-team"><img src="${data["teams"]["away"]["logo"]}" alt=""><span>${data["teams"]["away"]["name"]}</span></div>`
    $('.match-info').html(str_status);

    let betDetails = "";
    data["bookmakers"]["bets"].forEach(element => {
        betDetails += `<div class="custom-long-dialog" data="${element["id"]}"><span>${element["name"]}</span><div class="row d-flex justify-content-between">`
        element["values"].forEach(betItem => {
            betDetails+=`<div class="col-md-4 betting-item"><button class="btn btn-betting-item" onclick="putBetItem($(this))"><span class="oddName">${betItem["value"]}</span><span class="oddValue">${betItem["odd"]}</span></button></div>`
        });
        betDetails +=`</div></div>`
    });
    $('.bet-details').html(betDetails);
}

function backToCategory(){
    $('.home').show();
    $('.home-child').show();
    $('.odds-detail').hide();
    $('.event_list').hide();
    $('.my-bets').hide();
}

function putBetItem(element){
    let betItemId = $('#betItemId').val();
    let betId = element.parent().parent().parent().attr("data");
    let betName = element.find(".oddName").html();
    let odds = element.find(".oddValue").html();
    let homeTeam = $('.match-info').find(".home-team").find('span').html();
    let awayTeam = $('.match-info').find(".away-team").find('span').html();
    $("#loadingModal").fadeIn();
    $.ajax({
        url: '/push-bet-item',
        method: 'post',
        data:{
            _token: $("#token").val(),
            fixtuerId: betItemId,
            id:betId,
            name: betName,
            odds: odds,
            homeTeam: homeTeam,
            awayTeam: awayTeam
        },
        success: function(response) {
            $("#loadingModal").fadeOut();
            let data = response["bets"];
            setMyBets(data);
        },
        error: function(error) {
            $("#loadingModal").fadeOut();
            
            if (error.status === 403) {
                showToast("You have to login.");
            } else {
                showToast("An error occurred. Please try again.");
            }
        }
    });
}

function delete_betitem(element){
    let betId = element.parent().parent().parent().attr("data");
    $.ajax({
        url: '/delete-bet-item',
        method: 'post',
        data:{
            _token: $("#token").val(),
            betId: betId
        },
        success: function(response) {
            let data = response["bets"];
            setMyBets(data);
        },
        error: function(error) {
            
        }
    });
}

function setMyBets(data){
    let str = "";
    data.forEach(element => {
        str += `<div class="betted-item custom-dialog" data="${element['id']}"><div class="event-info"><div><i class="fa fa-football"></i>&nbsp<span>Europe Champion</span></div><div><button onclick="delete_betitem($(this))"><i class="fa fa-close"></i></button></div></div><div class="betting-name"><span>${!element['home_team'] ? "" : element['home_team']} vs ${!element['away_team'] ? "" : element["away_team"] }</span></div><div class="betted-info"><span>${element['bet_type']}</span><span>${element['bet_value']}</span></div><div><input type="text" onclick="number_inputer($(this))" class="form-control number-panel" /><div class="number-buttons" style="display: none;">
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 1)">1</button>
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 2)">2</button>
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 3)">3</button>
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 4)">4</button>
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 5)">5</button>
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 6)">6</button>
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 7)">7</button>
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 8)">8</button>
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 9)">9</button>
        <button class="btn btn-outline-dark" onclick="enter_number($(this), 0)">0</button>
    </div></div></div>`;
    });
    str+=`<div class="bet-total"><button class="btn btn-outline-dark" onclick="place_bets($(this))">Place Bets</button></div>`;

    $('.betslip').html(str);
}

function place_bets(element) {
    let bets = [];
    let hasError = false;
    
    $('.betted-item').each(function() {
        let amount = $(this).find('.number-panel').val();
        
        // Check if amount is entered
        if (!amount || amount === '') {
            $(this).find('.number-panel').addClass('error');
            hasError = true;
            return;
        }

        let bet = {
            id: $(this).attr('data'),
            amount: amount,
            bet_type: $(this).find('.betted-info span:first').text(),
            bet_value: $(this).find('.betted-info span:last').text(),
            teams: $(this).find('.betting-name span').text()
        };
        bets.push(bet);
    });

    // Don't proceed if there are errors
    if (hasError) {
        alert('Please enter amount for all bets');
        return;
    }

    // Proceed with placing bets
    if (bets.length > 0) {
        $.ajax({
            url: '/place-bets',
            method: 'POST',
            data: {
                _token: $("#token").val(),
                bets: bets 
            },
            success: function(response) {
                // Clear form or show success message
                showToast(response.message);
                location.href = '/';
            },
            error: function(error) {
                alert('Error placing bets');
            }
        });
    }
}

// Function to handle input focus
function number_inputer(input) {
    // Hide all other number-buttons first
    $('.number-buttons').hide();
    // Show only the number-buttons for this input
    input.siblings('.number-buttons').show();
}

function enter_number(element, number) {
    let input = element.parent().parent().find('.number-panel');
    let currentVal = input.val();
    
    if (!currentVal || currentVal === '') {
        input.val(number);
    } else {
        input.val(currentVal*10 + number);
        input.value = input.value/10;
    }
}

function fetchonlylivebets(){
    $.ajax({
        url: '/fetch-only-live-bets',
        method: 'GET',
        success: function(response) {
            $("#loadingModal").fadeOut();
            let data = response['bets'];
            updateEventList(data);
        },
        error: function(error) {
            $("#loadingModal").fadeOut();
        }
    });
}
// Add click handlers for number buttons
$(document).on('click', '.number-buttons button', function() {
    // Get the input field that's a sibling to this button's parent
    let input = $(this).closest('div').siblings('input');
    // Add the number to input value
    input.val(input.val() + $(this).text());
});

// Hide number-buttons when clicking outside
$(document).on('click', function(e) {
    if (!$(e.target).closest('.betted-item').length) {
        $('.number-buttons').hide();
    }
});
