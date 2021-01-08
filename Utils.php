<?php


/** Replace the last occurence of a substring in a string */
function ajaxds_str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);

    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}


/** Get a GUID  */
function ajaxds_GUID()
{
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }

    $val = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    return $val;
}


/** Get the current full URL */
function ajaxds_getCurrentUrl()
{
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}


/** Get the current visitor IP address */
function ajaxds_getVisitorIp()
{
    $ip = getenv('HTTP_CLIENT_IP') ?:
        getenv('HTTP_X_FORWARDED_FOR') ?:
        getenv('HTTP_X_FORWARDED') ?:
        getenv('HTTP_FORWARDED_FOR') ?:
        getenv('HTTP_FORWARDED') ?:
        getenv('REMOTE_ADDR');
    return $ip;
}


function ajaxds_escQuotesAndTrim($val)
{
    return trim(addslashes($val));
}


function ajaxds_sanitizeBasicTextInput($text)
{
    return sanitize_text_field(strval($text), true);
}


function ajaxds_safeEncodeBase64($value)
{
    $encoded = null;
    $errMessage = '<script>console.error("Dynamic Shortcode Ajax - One of your GET or POST parameter you are trying to send is either empty or an invalid string.");</script>';

    try {
        $encoded = base64_encode(strval($value));
        if (trim(strlen($encoded)) <= 0) {
            return $errMessage;
        }
    } catch (Throwable $th) {
        return $errMessage;
    }

    if ($encoded === null) {
        return $errMessage;
    }

    return $encoded;
}



function ajaxds_safeDecodeBase64($value)
{
    $decoded = null;
    $errMessage = '<script>console.error("Dynamic Shortcode Ajax - Invalid base64 parameter: Cannot decode one of your POST or GET parameter.");</script>';
    try {
        $decoded = base64_decode($value, true);
        if ($decoded === false) {
            wp_send_json(array("success" => false, "data" => $errMessage));
        }
    } catch (Throwable $th) {
        wp_send_json(array("success" => false, "data" => $errMessage));
    }

    if ($decoded === null) {
        wp_send_json(array("success" => false, "data" => $errMessage));
    }

    return $decoded;
}


function ajaxds_sanitizePlaceholder($text)
{
    if ($text === null) {
        wp_send_json(array("success" => false, "data" => "The placeholder must be non-empty, valid html."));
    }

    try {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadHTML('<html><head></head><body>' . strval($text) . '</body></html>');

        // Get all the script and iframe tags
        $script_tags = $dom->getElementsByTagName('script');
        $length = $script_tags->length;
        $length = $length + $dom->getElementsByTagName('iframe')->length;

        if ($length > 0) {
            wp_send_json(array("success" => false, "data" => "You cannot use script or iframe tags."));
        }

        if ($dom->saveHTML() === '<html><head></head><body></body></html>') {
            wp_send_json(array("success" => false, "data" => 'The placeholder must be non-empty, valid html.'));
        }
    } catch (Throwable $th) {
        wp_send_json(array("success" => false, "data" => "An error occured while trying to parse your placeholder HTML."));
    }

    return str_replace('\"', '"', str_replace('\'\"', '"', $text));
}

/** Enable JS debug console log if WP_DEBUG is activated */
function ajaxds_getDebugJs()
{
    $debug = '';
    if (defined('WP_DEBUG') && true === WP_DEBUG) {
        $debug = 'console.log(data);';
    }
    return $debug;
}
