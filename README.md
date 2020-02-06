# TraktPHPScrobble
PHP script for getting scrobble information from Trakt.TV

This is horrible, like, horrible.  I made this a few years ago and recently updated it a bit so it's actually got some form of documentation.  It's horrific.  It works, but it's horrific. 

# How To Use
You need to get a _Client ID_ by creating a Development App on [trakt.tv](https://trakt.tv/oauth/applications). The redirect URI can be anything, I just left mine as https://localhost for this.  Once you have the _Client ID_, you need to update the `$key` and `$user` variables in the api.trakttv.php file at the top.

In your file, include the api.trakttv.php file and call the `traktScrobbleResponse($user, $key)` function. This will output the array, then can use a for each to get the data out of the array. 
