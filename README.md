# TraktPHPScrobble
PHP script for getting scrobble information from Trakt.TV

This is horrible, like, horrible.  I made this a few years ago and recently updated it a bit so it's actually got some form of documentation.  It's horrific.  It works, but it's horrific. 

# How To Use
You need to get a _Client ID_ by creating a Development App on [trakt.tv](https://trakt.tv/oauth/applications). The redirect URI can be anything, I just left mine as https://localhost for this.  Once you have the _Client ID_, you need to update the `$key` and `$user` variables in the api.trakttv.php file at the top.

In your file, include the api.trakttv.php file and call the `traktScrobbleResponse($user, $key)` function. This will output the array, then can use a for each to get the data out of the array. 

# Data Ouput

There are two type's of data that will be returned from this script.  If a user is currently scrobbling, or, their watchlist if they not scrobbling. 

## Scrobbling Data
```
Array
(
    [started_at] => 2020-02-06T14:46:26.000Z
    [finishes_at] => 2020-02-06T15:30:26.000Z
    [type] => episode
    [episode] => Array
        (
            [season] => 1
            [episode_number] => 3
            [title] => The End is the Beginning
            [trakt-id] => 3913104
        )

)
```

## History Data
```
Array
(
    [0] => Array
        (
            [watched_at] => 2020-02-06T13:18:08.000Z
            [type] => movie
            [movie] => Array
                (
                    [title] => The Revenant
                    [year] => 2015
                    [trakt-id] => 179334
                )

        )

    [1] => Array
        (
            [watched_at] => 2020-02-06T04:05:02.000Z
            [type] => episode
            [show] => Array
                (
                    [show_name] => Chilling Adventures of Sabrina
                    [show_year] => 2018
                    [trakt-id] => 125890
                )

            [episode] => Array
                (
                    [episode_season] => 2
                    [episode_number] => 8
                    [episode_title] => Chapter Twenty-Eight: Sabrina Is Legend
                    [trakt-id] => 3871573
                )

        )

    [2] => Array
        (
            [watched_at] => 2020-02-06T03:04:54.000Z
            [type] => episode
            [show] => Array
                (
                    [show_name] => Chilling Adventures of Sabrina
                    [show_year] => 2018
                    [trakt-id] => 125890
                )

            [episode] => Array
                (
                    [episode_season] => 2
                    [episode_number] => 7
                    [episode_title] => Chapter Twenty-Seven: The Judas Kiss
                    [trakt-id] => 3871572
                )

        )

    [3] => Array
        (
            [watched_at] => 2020-02-06T02:04:48.000Z
            [type] => episode
            [show] => Array
                (
                    [show_name] => Chilling Adventures of Sabrina
                    [show_year] => 2018
                    [trakt-id] => 125890
                )

            [episode] => Array
                (
                    [episode_season] => 2
                    [episode_number] => 6
                    [episode_title] => Chapter Twenty-Six: All of Them Witches
                    [trakt-id] => 3874657
                )

        )

    [4] => Array
        (
            [watched_at] => 2020-02-06T01:05:37.000Z
            [type] => episode
            [show] => Array
                (
                    [show_name] => Chilling Adventures of Sabrina
                    [show_year] => 2018
                    [trakt-id] => 125890
                )

            [episode] => Array
                (
                    [episode_season] => 2
                    [episode_number] => 5
                    [episode_title] => Chapter Twenty-Five: The Devil Within
                    [trakt-id] => 3871571
                )

        )

)
```
