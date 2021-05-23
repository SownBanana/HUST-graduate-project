<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::channel('App.Global', function () {
    return true;
});
Broadcast::channel('App.PrivateMessage.{id}', function ($user, $id) {
    // dump("user id: ", $user->id, "channel:", $id);
    return (int)$user->id === (int)$id;
});
//Broadcast::channel('App.CourseComment.{course_id}', function ($user, $username) {
//    // return $user->username === $username;
//    return true;
//});
//Broadcast::channel('App.PrivateMessage.{username}', function ($user, $username) {
//    return $user->username === $username;
//});
