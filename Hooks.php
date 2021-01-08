<?php

function ajaxds_logout_redirect()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (
        isset($_SESSION['wp_dynamic_shortcode_usr_SESSID']) &&
        isset(ajaxds_getSessionVars($_SESSION['wp_dynamic_shortcode_usr_SESSID'], false)->expiration_date)
    ) {
        $sessId = $_SESSION['wp_dynamic_shortcode_usr_SESSID'];
        ajaxds_deleteSessionById($sessId);
        unset($_SESSION['wp_dynamic_shortcode_usr_SESSID']);
    }
}
add_action('wp_logout', 'ajaxds_logout_redirect');


function ajaxds_js_head()
{
    $globSearch = ajaxds_getJsVariableHtml();
    $mainAjax = ajaxds_getJsGetShortcodeAjaxHtml();

    $js = <<<EOD
    <script type="text/javascript">
    $globSearch
    $mainAjax
    </script>
EOD;
    echo $js;
}
add_action('wp_head', 'ajaxds_js_head');
