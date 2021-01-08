<?php


function ajaxds_dynamicReplaceInit()
{
    add_filter('the_content', 'ajaxds_dynamicReplaceBeforeLoad');
}
add_action('loop_start', 'ajaxds_dynamicReplaceInit');


function ajaxds_dynamicReplaceBeforeLoad($content)
{
    $newContent = $content;

    //All shortcodes with Dynamic Replace enabled
    $shortcodesDynReplace = ajaxds_getAllDynamicReplace();

    foreach ($shortcodesDynReplace as $res) {
        //Closing shortcode: Find all occurences then replace them
        $shortcodeName = $res->shortcode_name;
        $pattern = '/\[' . $shortcodeName . '(.*?)?\](?:(.+?)?\[\/' . $shortcodeName . '\])?/';
        $matches = null;
        preg_match_all($pattern, $newContent, $matches);


        for ($i = 0; $i < count($matches[1]); $i++) {
            $fullMatch = $matches[0][$i];

            if (strpos($fullMatch, '[/') !== false) {
                $contentOfShortcode = $matches[2][$i];

                $leftPartShortcode = explode(']', $fullMatch)[0];
                $attributes = str_replace('[' . $shortcodeName, '', $leftPartShortcode);

                if (strlen($attributes) > 0) {
                    $attributes = ' ' . $attributes;
                }

                $dynamicShortcodeFinal = '[wp_dynamic shortcode="' . $shortcodeName . '"' . $attributes . ']' . $contentOfShortcode . '[/wp_dynamic]';
                $newContent = str_replace($fullMatch, $dynamicShortcodeFinal, $newContent);
            }
        }


        //Self closing shortcode: Find all occurences then replace them
        $pattern = '/\[' . $shortcodeName . '(.*?)?\]/'; //'/\[' . $shortcodeName . '[^.\[\]]+\]/';
        $matches = null;
        preg_match_all($pattern, $newContent, $matches);

        for ($i = 0; $i < count($matches[0]); $i++) {
            $fullMatch = $matches[0][$i];
            $dynamicShortcodeFinal = ajaxds_getSelfClosingShortcode($fullMatch, $shortcodeName);
            $newContent = str_replace($fullMatch, $dynamicShortcodeFinal, $newContent);
        }
    }

    return $newContent;
}


function ajaxds_getSelfClosingShortcode($fullMatch, $shortcodeName)
{
    $attributes = str_replace('[' . $shortcodeName, '', $fullMatch);
    $attributes = substr($attributes, 0, -1);

    if (strlen($attributes) > 0) {
        $attributes = ' ' . $attributes;
    }

    $dynamicShortcodeFinal = '[wp_dynamic shortcode="' . $shortcodeName . '"' . $attributes . ']';
    return $dynamicShortcodeFinal;
}
