<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('live-odds', function () {
    return true; // Public channel, open for all users
});
