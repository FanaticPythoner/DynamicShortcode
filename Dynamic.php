<?php

function ajaxds_ajax_dynamic_shortcode()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Prevent XSS for all GET and POST parameters and filter every GET and POST parameters for safe base64 decode
    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $shortcodeName = ajaxds_sanitizeBasicTextInput($_POST['wp_dynamic_ajax_shortcode']);

    $ip = ajaxds_getVisitorIp();
    check_ajax_referer('ajaxDynamicShortcode_' . $shortcodeName . "_" . $ip, 'nonce');


    if (!isset($_SESSION['wp_dynamic_shortcode_usr_SESSID'])) {
        wp_send_json(array("success" => false, "data" => 'Invalid session cookie'));
    }


    $vals = ajaxds_getSessionVars($_SESSION['wp_dynamic_shortcode_usr_SESSID'], true);
    if ($vals === 0) {
        wp_send_json(array("success" => false, "data" => 'Invalid session cookie'));
    }

    $shortcodeisValid = ajaxds_getIfShortcodeIsCallable($shortcodeName);
    if ($shortcodeisValid === 0) {

        wp_send_json(
            array(
                "success" => false,
                "data" => 'This shortcode cannot be called via Dynamic Shortcode. Go the the "Global Settings" tab in your wp-admin menu and allow this shortcode to be editable.'
            )
        );
    }

    $arrData = ajaxds_getDataFromString($vals->data);

    ajaxds_initTheLoopGlobals($arrData);
    ajaxds_initBrowserDetectionGlobals($arrData);
    ajaxds_initWebServerDetectionGlobals($arrData);
    ajaxds_initVersionGlobals($arrData);
    ajaxds_initAdminGlobals($arrData);
    ajaxds_initMiscGlobals($arrData);


    $postGetArrs = ajaxds_getPostGetParamsSession($_SESSION['wp_dynamic_shortcode_usr_SESSID']);
    if (is_array($postGetArrs) && count($postGetArrs) === 2) {
        $post = $postGetArrs['post'];
        $get = $postGetArrs['get'];

        if (!is_array($_GET)) {
            $_GET = array();
        }

        foreach ($get as $k => $val) {
            $key = strval($k);
            $_GET[$key] = $get[$key];
        }


        if (!is_array($_POST)) {
            $_POST = array();
        }

        foreach ($post as $k => $val) {
            $key = strval($k);
            $_POST[$key] = $post[$key];
        }
    } else {
        wp_send_json(array("success" => false, "data" => 'An error occured while trying to recover your POST and GET parameters for your shortcode.'));
    }

    wp_send_json(array("data" => do_shortcode('[' . $shortcodeName . ']')));
}
add_action('wp_ajax_dynamic_shortcode', 'ajaxds_ajax_dynamic_shortcode');
add_action('wp_ajax_nopriv_dynamic_shortcode', 'ajaxds_ajax_dynamic_shortcode');



function ajaxds_dynamicShortcodeFunc($atts)
{
    $mainShortcode = $atts['shortcode'];
    if (strlen($mainShortcode) === 0) {
        return "The target shortcode cannot be empty";
    }

    $errorMsg = "";
    $paramsShortcode = ajaxds_getShortcodeParameters($mainShortcode);

    if ($paramsShortcode === null) {
        // $paramsShortcode = ajaxds_getDefaultParametersShortcode();
        // $res = ajaxds_updateInsertShortcodeSettings(
        //     $mainShortcode,
        //     "0",
        //     "",
        //     "",
        //     "",
        //     "0",
        //     "default",
        //     "",
        //     false
        // );
        $errorMsg = 'Shortcode name is invalid, or you haven\'t set any settings for this shortcode in the admin panel.';
    }

    if (strlen($errorMsg) > 0 && strlen($paramsShortcode->validation_function) > 0) {
        $errorMsg = call_user_func($paramsShortcode->validation_function);
    }


    $outputReturn = null;
    if (strlen($errorMsg) === 0) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $sessId = null;

        if (
            isset($_SESSION['wp_dynamic_shortcode_usr_SESSID']) &&
            isset(ajaxds_getSessionVars($_SESSION['wp_dynamic_shortcode_usr_SESSID'], false)->expiration_date)
        ) {
            $sessId = $_SESSION['wp_dynamic_shortcode_usr_SESSID'];
        }

        $skipUpdateInsert = null;
        $isUpdate = null;
        $resSess = ajaxds_getSessionVars($sessId);
        $currUrl = ajaxds_getCurrentUrl();
        if ($resSess === 0) {
            $isUpdate = false;
            $skipUpdateInsert = false;
        } else if ($currUrl !== $resSess->current_url) {
            $skipUpdateInsert = false;
            $isUpdate = true;
        } else if (isset($_SESSION['wp_dynamic_shortcode_usr_SESSID'])) {
            $skipUpdateInsert = true;
            $isUpdate = true;
        }


        $ignoreParams = null;
        if (strlen($paramsShortcode->get_parameters_ignore) > 0) {
            $ignoreParams = explode('\n', $paramsShortcode->get_parameters_ignore);
        } else {
            $ignoreParams = array();
        }

        $ignoreParamsPost = null;
        if (strlen($paramsShortcode->post_parameters_ignore) > 0) {
            $ignoreParamsPost = explode('\n', $paramsShortcode->post_parameters_ignore);
        } else {
            $ignoreParamsPost = array();
        }

        $ignoreParams = $ignoreParams + $ignoreParamsPost;

        if (!$skipUpdateInsert) {
            $arrToSet = array();
            $arrToSet = $arrToSet + ajaxds_setTheLoopGlobals();
            $arrToSet = $arrToSet + ajaxds_setBrowserDetectionGlobals();
            $arrToSet = $arrToSet + ajaxds_setWebServerDetectionGlobals();
            $arrToSet = $arrToSet + ajaxds_setVersionGlobals();
            $arrToSet = $arrToSet + ajaxds_setAdminGlobals();
            $arrToSet = $arrToSet + ajaxds_setMiscGlobals();

            $getArr = array();

            if (is_array($_GET)) {
                $GETKeys = array_keys($_GET);
                foreach ($GETKeys as $key) {
                    $keyCurr = strval($key);
                    if (!in_array($keyCurr, $ignoreParams)) {
                        //No matter what the string is, the value is safe to display in HTML/JS
                        $safeHtmlJSDisplay = ajaxds_safeEncodeBase64($_GET[$keyCurr]);
                        $getArr = $getArr + array('wp_dynamic_GETPARAM_' . $keyCurr => $safeHtmlJSDisplay);
                    }
                }
            }


            if (is_array($_POST)) {
                $POSTKeys = array_keys($_POST);
                foreach ($POSTKeys as $key) {
                    $keyCurr = strval($key);
                    if (!in_array($keyCurr, $ignoreParams)) {
                        //No matter what the string is, the value is safe to display in HTML/JS
                        $safeHtmlJSDisplay = ajaxds_safeEncodeBase64($_POST[$keyCurr]);
                        $getArr = $getArr + array('wp_dynamic_POSTPARAM_' . $keyCurr => $safeHtmlJSDisplay);
                    }
                }
            }

            ajaxds_setSessionVars($sessId, $arrToSet, $isUpdate, $getArr);
        }



        $placeholder = ajaxds_getPlaceholderValue($paramsShortcode->placeholder_name);
        $placeholder = str_replace('[_MAIN_SHORTCODE_]', $mainShortcode, $placeholder);

        $jsFunc = null;
        if (strval($paramsShortcode->is_javascript_variable) === "1") {
            $jsFunc = true;
        } else {
            $jsFunc = false;
        }


        $outputReturn = ajaxds_getJsDynamic($mainShortcode, $placeholder, $jsFunc);
    } else {
        $outputReturn = $errorMsg;
    }

    return $outputReturn;
}
add_shortcode('wp_dynamic', 'ajaxds_dynamicShortcodeFunc');


function ajaxds_getJsDynamic($mainShortcode, $placeholder, $isJsVariable)
{
    $url = '/wp-admin/admin-ajax.php';
    $ip = ajaxds_getVisitorIp();
    $nonce_ = wp_create_nonce('ajaxDynamicShortcode_' . $mainShortcode . "_" . $ip);
    $ajaxParamsReq = array('wp_dynamic_ajax_shortcode' => $mainShortcode, 'nonce' => $nonce_) + array('action' => 'dynamic_shortcode');


    $data = json_encode($ajaxParamsReq);
    $mainShortcodeHtml = ajaxds_escQuotesAndTrim(htmlentities($mainShortcode));
    $isJsVariableStr = ajaxds_escQuotesAndTrim(strval($isJsVariable));
    $idStrHtml = ajaxds_escQuotesAndTrim("wp_dynamic_shortcode_placeholder_$mainShortcodeHtml");
    $placeholder = ajaxds_escQuotesAndTrim($placeholder);
    $toReplaceEl = null;
    $dicParamName = "ajaxds_dicParams_$mainShortcodeHtml";


    if ($isJsVariable === true) {
        $toReplaceEl = "`$idStrHtml`;";
    } else {
        $toReplaceEl = <<<EOD
        <div id="$idStrHtml"></div>
        <script async>
EOD;
    }


    $js = <<<EOD
    $toReplaceEl
    var $dicParamName = {
        "ajaxds_shortcodeDivId_$mainShortcodeHtml":"$idStrHtml",
        "ajaxds_data_$mainShortcodeHtml":$data,
        "ajaxds_url_$mainShortcodeHtml":"$url",
        "ajaxds_shortcode_id_$mainShortcodeHtml":"$mainShortcodeHtml",
        "ajaxds_isJsStr_$mainShortcodeHtml":"$isJsVariableStr",
        "ajaxds_placeholder_$mainShortcodeHtml":"$placeholder"
    };
    ajaxds_dynamicShortcodeAjax($dicParamName [`ajaxds_url_$mainShortcodeHtml` ], $dicParamName [`ajaxds_data_$mainShortcodeHtml`], 
                                $dicParamName [`ajaxds_shortcodeDivId_$mainShortcodeHtml`], $dicParamName [`ajaxds_shortcode_id_$mainShortcodeHtml`],
                                $dicParamName [`ajaxds_isJsStr_$mainShortcodeHtml`], $dicParamName [`ajaxds_placeholder_$mainShortcodeHtml`]);
EOD;

    if (!$isJsVariable) {
        $js .= '</script>';
    }

    return $js;
}
