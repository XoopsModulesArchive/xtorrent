<?php

/*
    bencode.reader.php
    Contains classes and functions to assist in the reading and processing of bencoded files and strings.
    This is a component of a BitTorrent tracker/torrent library.
    This library is free to redistribute, but I'd appreciate if you left the credit in if you use it.
    Written by Greg Poole | m4dm4n@gmail.com | http://m4dm4n.homelinux.net:8086
*/

class BEncodeReader
{
    public $data;

    public $pointer = 0;

    public $error = false;

    public function __construct($filename = null)
    {
        ini_set('allow_url_fopen', true);

        if (null === $filename) {
            return;
        }

        if (!is_file($filename)) {
            trigger_error("Could not create BEncodeReader for {$filename}: it does not exist", E_USER_WARNING);

            return;
        }

        $h = @fopen($filename, 'rb');

        if (false === $h) {
            trigger_error("Could not create BEncodeReader for {$filename}: failed to open for reading", E_USER_WARNING);

            return;
        }

        $filesize = @filesize($filename);

        if (false === $filesize) {
            trigger_error("Could not create BEncodeReader for {$filename}: the file is empty", E_USER_WARNING);

            return;
        }

        $this->data = @fread($h, $filesize);

        if (false === $this->data) {
            trigger_error("Error creating BEncodeReader for {$filename}: error reading from file", E_USER_WARNING);
        }

        @fclose($h);
    }

    // Read the next part in the current file

    public function readNext()
    {
        if (!isset($this->data)) {
            return false;
        }

        if ('e' == $this->data[$this->pointer]) {
            // Except in the case of an error or malformed data string, the letter e will mark the end of anything we've been reading, so we move

            // the pointer and retreat.

            $this->pointer++;

            return false;
        }

        if ('d' == $this->data[$this->pointer]) {
            // d marks the start of a dictionary, which is essentially an associative array

            $start = $this->pointer;

            $this->pointer++;

            $dictionary = [];

            $current = false;

            while (false !== ($value = $this->readNext())) {
                if (false === $current) {
                    $current = $value;
                } else {
                    $dictionary[$current] = $value;

                    $current = false;
                }
            }

            if (0 == count($dictionary) || $this->error) {
                $this->error = true;

                return false;
            }

            $end = $this->pointer;

            $dictionary['hash'] = pack('H*', sha1(mb_substr($this->data, $start, $end - $start)));

            return $dictionary;
        } elseif ('l' == $this->data[$this->pointer]) {
            // An l indicates the start of a list, which is essentially an array, so we will read it as such

            $this->pointer++;

            $list = [];

            for ($i = 0; false !== ($value = $this->readNext()); $i++) {
                $list[$i] = $value;
            }

            if (0 == count($list) || $this->error) {
                return false;
            }

            return $list;
        } elseif ('i' == $this->data[$this->pointer]) {
            // The following data is an integer, so it will be read until the next "e", after which it will be returned

            $this->pointer++;

            $endPosition = mb_strpos($this->data, 'e', $this->pointer);

            // A failure to find an endpoint within a resonable distance means that this is not a valid integer definition.

            if (false === $endPosition || ($endPosition - $this->pointer) > 10) {
                $this->error = true;

                return false;
            }

            $readLength = ($endPosition - $this->pointer);

            $int = mb_substr($this->data, $this->pointer, $readLength);

            $this->pointer += $readLength + 1;

            return $int;
        }  

        $nextColon = mb_strpos($this->data, ':', $this->pointer);

        // The only thing possible here is a string definition, which is ###:string. Without a colon,

        // this is not a string definition and we can assume the file is broken. I'm also including

        // a check to make sure that colons over 5 characters away will be ignored to prevent a

        // broken file from locking up the system.

        if (false === $nextColon || ($nextColon - $this->pointer) > 5) {
            $this->error = true;

            return false;
        }

        $length = mb_substr($this->data, $this->pointer, $nextColon);

        $readLength = ($nextColon - $this->pointer);

        $this->pointer += $readLength + 1;

        $string = mb_substr($this->data, $this->pointer, $length);

        $this->pointer += mb_strlen($string);

        return $string;
    }
}
