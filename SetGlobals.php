<?php 

function dynamicShortcodeEncrypt($value){
    return $value;
}


function ajaxds_setTheLoopGlobals(){
    global $posts;
    global $authordata;
    global $currentday;
    global $currentmonth;
    global $page;
    global $pages;
    global $multipage;
    global $more;
    global $numpages;

    $toSetArr = array(
        'wp_core_global_$post' => null,
        'wp_core_global_$user' => serialize(get_current_user_id()),
        'wp_core_global_$wp_query' => serialize($GLOBALS['wp_query']),
        'wp_core_global_$posts' => serialize($posts),
        'wp_core_global_$authordata' => serialize($authordata),
        'wp_core_global_$currentday' => serialize($currentday),
        'wp_core_global_$currentmonth' => serialize($currentmonth),
        'wp_core_global_$page' => serialize($page),
        'wp_core_global_$pages' => serialize($pages),
        'wp_core_global_$multipage' => serialize($multipage),
        'wp_core_global_$more' => serialize($more),
        'wp_core_global_$numpages' => serialize($numpages),

    );
    
    global $post;
    if (isset($post)){
        $toSetArr = $toSetArr + array('wp_core_global_$post' => serialize($post->ID));
    }

    return $toSetArr;
}


function ajaxds_setBrowserDetectionGlobals(){
    global $is_iphone;
    global $is_chrome;
    global $is_safari;
    global $is_NS4;
    global $is_opera;
    global $is_macIE;
    global $is_winIE;
    global $is_gecko;
    global $is_lynx;
    global $is_IE;
    global $is_edge;

    $toSetArr = array(
        'wp_core_global_$is_iphone' => serialize($is_iphone),
        'wp_core_global_$is_chrome' => serialize($is_chrome),
        'wp_core_global_$is_safari' => serialize($is_safari),
        'wp_core_global_$is_NS4' => serialize($is_NS4),
        'wp_core_global_$is_opera' => serialize($is_opera),
        'wp_core_global_$is_macIE' => serialize($is_macIE),
        'wp_core_global_$is_winIE' => serialize($is_winIE),
        'wp_core_global_$is_gecko' => serialize($is_gecko),
        'wp_core_global_$is_lynx' => serialize($is_lynx),
        'wp_core_global_$is_IE' => serialize($is_IE),
        'wp_core_global_$is_edge' => serialize($is_edge),

    );

    return $toSetArr;
}


function ajaxds_setWebServerDetectionGlobals(){
    global $is_apache;
    global $is_IIS;
    global $is_nginx;
    global $is_iis7;

    $toSetArr = array(
        'wp_core_global_$is_apache' => serialize($is_apache),
        'wp_core_global_$is_IIS' => serialize($is_IIS),
        'wp_core_global_$is_iis7' => serialize($is_iis7),
        'wp_core_global_$is_nginx' => serialize($is_nginx),

    );

    return $toSetArr;
}


function ajaxds_setVersionGlobals(){
    global $wp_version;
    global $wp_db_version;
    global $tinymce_version;
    global $manifest_version;
    global $required_php_version;
    global $required_mysql_version;

    $toSetArr = array(
        'wp_core_global_$wp_version' => serialize($wp_version),
        'wp_core_global_$wp_db_version' => serialize($wp_db_version),
        'wp_core_global_$tinymce_version' => serialize($tinymce_version),
        'wp_core_global_$manifest_version' => serialize($manifest_version),
        'wp_core_global_$required_php_version' => serialize($required_php_version),
        'wp_core_global_$required_mysql_version' => serialize($required_mysql_version),

    );

    return $toSetArr;
}


function ajaxds_setMiscGlobals(){
    global $super_admins;
    global $wp_rewrite;
    global $wp;
    global $wp_locale;
    global $wp_admin_bar;
    global $wp_roles;
    global $wp_meta_boxes;
    global $wp_registered_sidebars;
    global $wp_registered_widgets;
    global $wp_registered_widget_controls;
    global $wp_registered_widget_updates;

    $toSetArr = array(
        'wp_core_global_$super_admins' => serialize($super_admins),
        'wp_core_global_$wp_rewrite' => serialize($wp_rewrite),
        'wp_core_global_$wp' => serialize($wp),
        'wp_core_global_$wp_locale' => serialize($wp_locale),
        'wp_core_global_$wp_admin_bar' => serialize($wp_admin_bar),
        'wp_core_global_$wp_roles' => serialize($wp_roles),
        'wp_core_global_$wp_meta_boxes' => serialize($wp_meta_boxes),
        'wp_core_global_$wp_registered_sidebars' => serialize($wp_registered_sidebars),
        'wp_core_global_$wp_registered_widgets' => serialize($wp_registered_widgets),
        'wp_core_global_$wp_registered_widget_controls' => ajaxds_safeEncodeBase64(serialize($wp_registered_widget_controls)),
        'wp_core_global_$wp_registered_widget_updates' => ajaxds_safeEncodeBase64(serialize($wp_registered_widget_updates)),

    );

    return $toSetArr;
}


function ajaxds_setAdminGlobals(){
    global $pagenow;
    global $post_type;
    global $allowedposttags;
    global $allowedtags;
    global $menu;

    $toSetArr = array(
        'wp_core_global_$pagenow' => serialize($pagenow),
        'wp_core_global_$post_type' => serialize($post_type),
        'wp_core_global_$allowedposttags' => serialize($allowedposttags),
        'wp_core_global_$allowedtags' => serialize($allowedtags),
        'wp_core_global_$menu' => serialize($menu),

    );

    return $toSetArr;
}