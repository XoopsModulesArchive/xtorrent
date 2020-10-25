<?php

/*
    tracker.php
    Classes and functions designed to facilitate communication with BitTorrent trackers using the
    BitTorrent protocol, in order to query them for information.
    This is a component of a BitTorrent tracker/torrent library.
    This library is free to redistribute, but I'd appreciate if you left the credit in if you use it.
    Written by Greg Poole | m4dm4n@gmail.com | http://m4dm4n.homelinux.net:8086
*/

require_once 'bencode.reader.php';

// Summarise the results of the tracker_scrape_all function, by adding up all of the scrape results
// into a single array of seeds, leeches and successful downloads.
function tracker_scrape_summarise($scrape_results)
{
    if (!is_array($scrape_results)) {
        trigger_error('tracker_scrape_summarise error: Expected array as first parameter.');

        return false;
    }

    $summary = ['seeds' => 0, 'leeches' => 0, 'downloads' => 0];

    foreach ($scrape_results as $result) {
        if (is_array($result)) {
            $summary['seeds'] += $result['seeds'];

            $summary['leeches'] += $result['leeches'];

            $summary['downloads'] += $result['downloads'];
        }
    }

    return $summary;
}

// Retrieve a full list of trackers available in the case where "announce-list" is specified.
// If "announce-list" is specified, then the default "announce" property will be ignored in favour
// of this. Otherwise, only the results of scraping the tracker specified by the "announce" property
// will be returned.
function tracker_scrape_all($torrent, $timeout = 5)
{
    if (!count($torrent->announceList)) {
        return [tracker_scrape($torrent)];
    }

    $scrape_results = [];

    foreach ($torrent->announceList as $tier) {
        foreach ($tier as $tracker) {
            $scrape_results[$tracker] = tracker_scrape($torrent, $tracker, $timeout);
        }
    }

    return $scrape_results;
}

// Retrieve information on the torrent object supplied by querying the tracker's
// announce address. If no tracker announce address is specified, then the default
// announce address will be used from the tracker object.
function tracker_scrape($torrent, $tracker = null, $timeout = 5)
{
    if (null === $tracker) {
        $tracker = $torrent->announce;
    }

    $scrape_address = tracker_get_scrape_address($tracker);

    if (false === $scrape_address) {
        trigger_error("Failed to scrape tracker {$tracker}", E_USER_WARNING);

        return false;
    }

    if (false !== mb_strpos($scrape_address, '?')) {
        $scrape_address .= '&info_hash=' . urlencode($torrent->infoHash);
    } else {
        $scrape_address .= '?info_hash=' . urlencode($torrent->infoHash);
    }

    // Set the timeout before proceeding and reset it when done

    $old_timeout = ini_get('default_socket_timeout');

    ini_set('default_socket_timeout', $timeout);

    $data = @file_get_contents($scrape_address);

    ini_set('default_socket_timeout', $old_timeout);

    // Something is wrong with the address or the HTTP response of the tracker, or the request timed out. It's hard to

    // say but something has clearly gone critically wrong.

    if (false === $data) {
        trigger_error('tracker_scrape error: Failed to scrape torrent details from the tracker', E_USER_WARNING);

        return false;
    }

    $reader = new BEncodeReader();

    $reader->data = $data;

    $trackerInfo = $reader->readNext();

    // A bad tracker response might be bad software, something the library doesn't understand or any number

    // of other weird issues. Regardless, we couldn't read it so we can't proceed.

    if (false === $trackerInfo) {
        trigger_error('tracker_scrape error: Tracker returned invalid response to scrape request', E_USER_WARNING);

        return false;
    }

    // The tracker doesn't want to give us information on the torrent we requested. They've given us a response as to why.

    if (isset($trackerInfo['failure reason'])) {
        $this->failureReason = $trackerInfo['failure reason'];

        trigger_error("tracker_scrape error: Scrape failed. Tracker gave the following reason: \"{$this->failureReason}\"", E_USER_WARNING);

        return false;
    }

    $result = [];

    $result['seeds'] = $trackerInfo['files'][$torrent->infoHash]['complete'];

    $result['downloads'] = $trackerInfo['files'][$torrent->infoHash]['downloaded'];

    $result['leeches'] = $trackerInfo['files'][$torrent->infoHash]['incomplete'];

    return $result;
}

// Get the address which can be used to scrape a tracker for information on a torrent, based
// on the announce address provided.
function tracker_get_scrape_address($announce)
{
    $last_slash = mb_strrpos($announce, '/');

    if (false === $last_slash) {
        trigger_error("Tracker address ({$announce}) is invalid", E_USER_WARNING);

        return false;
    }

    $last_part = mb_substr($announce, $last_slash);

    if (false === mb_strpos($last_part, 'announce')) {
        trigger_error("Tracker ({$announce}) does not appear to support scrape", E_USER_WARNING);

        return false;
    }

    return mb_substr($announce, 0, $last_slash) . '/' . str_replace($last_part, 'announce', 'scrape');
}
