<?php
// Author: Laim McKenzie
// Repo: https://github.com/laim/TraktPHPScrobble
// License: MIT

$k = "TRAKT_API_KEY";
$u = "TRAKT_USER";

function trakt($u, $t, $k) {

    /**
  * 
  * Returns t/f if user is scrobbling
  *
  * @param string $k  trakt key
  * @param string $u  trakt username
  * @param string $t  trakt type (watching, history etc)
  * @return array json_decode response
  */

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://api.trakt.tv/users/$u/$t");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "trakt-api-version: 2",
    "trakt-api-key: $k"
  ));

  $response = curl_exec($ch);
  curl_close($ch);

  return json_decode($response, true);
}

function scrobbleStatus($k, $u) {
  /**
  * 
  * Returns t/f if user is scrobbling
  *
  * @param string $k  trakt key
  * @param string $u  trakt username
  * @return boolean
  */

  if(empty(trakt($u, "watching", $k)['expires_at'])) {
    return false;
  } else {
    return true;
  }
}

function traktResponse($k, $u) {
  if(scrobbleStatus($k, $u)) {
    return trakt($u, "watching", $k);
  } else {
    return trakt($u, "history", $k);
  };
}

var_dump(traktResponse($k, $u));

?>
