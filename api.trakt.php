<?php
//       Author: Laim McKenzie
//        Repo: https://github.com/laim/TraktPHPScrobble
//     License: MIT
// Description: Returns either the current watching status (show/movie) or most recently watched

// API Configuration //
$key = "client_id";
$user = "laim";
// API Configuration //

$trakt = array();

function dateFormat($date) {
  return date("yy-m-d h:i A", strtotime($date))
  ;
}
function traktAPI($user, $key, $type) {
  /**
  * Returns t/f if user is scrobbling
  * @param string $user   trakt username
  * @param string $key    trakt key
  * @param string $type   trakt type (watching, history etc)
  * @return array json_decode response
  */
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.trakt.tv/users/$user/$type");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "trakt-api-version: 2",
    "trakt-api-key: $key"
  ));
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true);
}

function traktScrobbleStatus($user, $key) {
  /**
  * Returns t/f if user is scrobbling
  * @param string $user   trakt username
  * @param string $key    trakt key
  * @return boolean
  */
  return ! empty(traktAPI($user, $key, "watching")['expires_at']);
}

function traktScrobbleType($user, $key) {
  /**
  * Returns string if user is scrobbling
  * @param string $user   trakt username
  * @param string $key    trakt key
  * @return string
  */

  $trakt_response = traktAPI($user, $key, "watching")['type'];
  if($trakt_response == "movie") {
    return "watching_movie";
  } elseif($trakt_response == "episode") {
    return "watching_tvshow";
  }
}

function traktScrobbleResponse($user, $key) {
  /**
  * Returns string if user is scrobbling
  * @param string $user   trakt username
  * @param string $key    trakt key
  * @return string
  */
  if(traktScrobbleStatus($user, $key)) { // if the user is scrobbling
    if(traktScrobbleType($user, $key) == "watching_movie") { // if the user is watching a movie
      
      $trakt_response = traktAPI($user, $key, "watching");
      $trakt = [
        "started_at" => $trakt_response['started_at'],
        "finishes_at" => $trakt_response['expires_at'],
        "custom_status" => "currently_watching",
        "type" => $trakt_response['type'],
        "movie" => array (
          "title" => $trakt_response['movie']['title'],
          "year" => $trakt_response['movie']['year'],
          "trakt-id" => $trakt_response['movie']['ids']['trakt']
        )
      ];
      return $trakt;

    } elseif(traktScrobbleType($user, $key) == "watching_tvshow") {
      
      $trakt_response = traktAPI($user, $key, "watching");
      $trakt = [
        "started_at" => $trakt_response['started_at'],
        "finishes_at" => $trakt_response['expires_at'],
        "custom_status" => "currently_watching",
        "type" => $trakt_response['type'],
        "show" => array (
          "show_name" => $trakt_response['show']['title'],
          "show_year" => $trakt_response['show']['year'],
          "trakt-id" => $trakt_response['show']['ids']['trakt']
        ),
        "episode" => array (
          "episode_season" => $trakt_response['episode']['season'],
          "episode_number" => $trakt_response['episode']['number'],
          "episode_title" => $trakt_response['episode']['title'],
          "trakt-id" => $trakt_response['episode']['ids']['trakt']
        )
      ];
      return $trakt;

    }
  } else {

    $trakt_response = traktAPI($user, $key, "history");
    $x = 0;
    while($x < 5) {
      if($trakt_response[$x]['type'] == "movie") {
        $trakt[] = [
          "watched_at" => $trakt_response[$x]['watched_at'],
          "custom_status" => "scrobbled",
          "type" => $trakt_response[$x]['type'],
          "movie" => array (
            "title" => $trakt_response[$x]['movie']['title'],
            "year" => $trakt_response[$x]['movie']['year'],
            "trakt-id" => $trakt_response[$x]['movie']['ids']['trakt']
          )
          ];
      } else {
        $trakt[] = [
          "watched_at" => $trakt_response[$x]['watched_at'],
          "custom_status" => "scrobbled",
          "type" => $trakt_response[$x]['type'],
          "show" => array (
            "show_name" => $trakt_response[$x]['show']['title'],
            "show_year" => $trakt_response[$x]['show']['year'],
            "trakt-id" => $trakt_response[$x]['show']['ids']['trakt']
          ),
          "episode" => array (
            "episode_season" => $trakt_response[$x]['episode']['season'],
            "episode_number" => $trakt_response[$x]['episode']['number'],
            "episode_title" => $trakt_response[$x]['episode']['title'],
            "trakt-id" => $trakt_response[$x]['episode']['ids']['trakt']
          )
        ];
      }
      $x++;
    }
  
    return $trakt;
    
  }
}

// this is a (horrible) example
function traktCustomOutput($data) {

  if(!$data['custom_status'] == "currently_watching") {
    $x = 0;
    foreach($data as $i)
    {
      if($i['type'] == "movie") {
        return "I last watched <b>" . $i['movie']['title'] . " (" . $i['movie']['year'] . ")</b> on " . dateFormat($i['watched_at']);
      } elseif($i['type'] == "episode") {
        return "I last watched <b>" . $i['show']['show_name'] . " (" . $i['show']['show_year'] . ") - " . $i['episode']['episode_title'] . "</b> on " . dateFormat($i['watched_at']);
      } else {
        return "Incorrect Type" . $data;
      }
      $x++;
    }
  } else {
    if($data['type'] == "movie") {
      return "I'm currently watching <b>" . $data['movie']['title'] . " (" . $data['movie']['year'] .")</b>.";
    } elseif ($data['type'] == "episode") {
      return "I'm currently watching <b>" . $data['show']['show_name'] . " (" . $data['show']['show_year'] .") - " . $data['episode']['episode_title'] . "</b>.";
    } else {
      return $data['type'];
    }
  }

}

/*
uncomment this section if you want to just test output
echo '<pre>' , print_r(traktScrobbleResponse($user, $key), true) , '</pre>';
*/

?>
