<?php

$cache = false;
$cachedir = ROOT . DS . 'tmp' . DS . 'cache';
$files = "";
$_ext = "." . $type;
// Determine last modification date of the files
$lastmodified = 0;

foreach ($elements as $element)
{
    $path = $element . $_ext;

    if (!file_exists($path))
    {
        header("HTTP/1.0 404 Not Found");
        echo $path;
        exit;
    }

    $files .= $element . $_ext . ',';
    $lastmodified = max($lastmodified, filemtime($path));
}

$files = rtrim($files, ',');

// Send Etag hash
$hash = $lastmodified . '-' . md5($files);

if (isset($_SERVER['HTTP_IF_NONE_MATCH']))
{
    if (stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == $hash)
    {
// Return visit and no modifications, so do not send anything
        header("HTTP/1.0 304 Not Modified");
        header('Content-Length: 0');
    }
}
else
{
    header('Etag: "' . $hash . '"');

// First time visit or files were modified
    if ($cache)
    {
// Determine supported compression method
        $gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
        $deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');

// Determine used compression method
        $encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');

// Check for buggy versions of Internet Explorer
        if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera'))
        {
            if (preg_match('/^Mozilla\/4\.0 \(compatible;MSIE ([ 0-9 ]\.[ 0-9 ])/i', $_SERVER['HTTP_USER_AGENT'], $matches))
            {
                $version = floatval($matches[1]);

                if ($version < 6)
                {
                    $encoding = 'none';
                }

                if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1'))
                {
                    $encoding = 'none';
                }
            }
        }

// Try the cache first to see if the combined files were already generated
        $cachefile = 'cache-' . $hash . '.' . $type . ($encoding != 'none' ? '.' . $encoding : '');

        if (file_exists($cachedir . DS . $cachefile))
        {
            if ($fp = fopen($cachedir . DS . $cachefile, 'rb'))
            {
                if ($encoding != 'none')
                {
                    header("Content-Encoding: " . $encoding);
                }

                header("Content-Type: text/" . $type);
                header("Content-Length: " . filesize($cachedir . DS . $cachefile));

                fpassthru($fp);
                fclose($fp);
                exit;
            }
        }
    }

// Get contents of the files
    $contents = '';

    $_file = '';
    reset($elements);

    foreach ($elements as $element)
    {
        $path = $element . $_ext;
        $_file = file_get_contents($path);
        $_file = str_replace("\xEF\xBB\xBF", '', $_file);

// Remove comments

//$_file = preg_replace ( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $_file );
        // Remove space after colons
        $_file = str_replace(': ', ':', $_file);
// Remove whitespace
        $contents .= $_file . "\n";
    }

// Send Content-Type
    header("Content-Type: text/" . $type);

    if (isset($encoding))
    {
        if ($encoding != 'none')
        {
// Send compressed contents
            $contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
            header("Content-Encoding: " . $encoding);
            header('Content-Length:' . strlen($contents));
            echo $contents;
        }
    }
    else
    {
// Send regular contents
        header('Content-Length:' . strlen($contents));
        echo $contents;
    }

// Store cache
    if ($cache)
    {
        if ($fp = fopen($cachedir . DS . $cachefile, 'wb'))
        {
            fwrite($fp, $contents);
            fclose($fp);
        }
    }
}
