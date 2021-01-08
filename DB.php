<?php


/** Delete a session by its ID */
function ajaxds_deleteSessionById($sessId)
{
    $sqlQuery = <<<EOD
    DELETE FROM wp_dynamic_shortcode
    WHERE id_session = %s
EOD;
    $res = ajaxds_doSql($sqlQuery, array($sessId), 'query');
}


/** Get all the session variables */
function ajaxds_getSessionVars($sessId, $includeData = false)
{
    $sqlQuery = <<<EOD
    SELECT expiration_date, current_url[_DATA_STR_]
    FROM wp_dynamic_shortcode
    WHERE id_session = %s
EOD;

    if ($includeData) {
        $sqlQuery = str_replace('[_DATA_STR_]', ', data', $sqlQuery);
    } else {
        $sqlQuery = str_replace('[_DATA_STR_]', '', $sqlQuery);
    }

    $res = ajaxds_doSql($sqlQuery, array($sessId), 'row');

    if (isset($res)) {
        if ($res->expiration_date < date("Y-m-d H:i:s")) {
            ajaxds_deleteSessionById($sessId);
            return 0;
        }

        return $res;
    } else {
        return 0;
    }

    return $res;
}


function ajaxds_getIfShortcodeIsCallable($shortcodeName){
    $sqlQuery = <<<EOD
    SELECT shortcode_name
    FROM wp_dynamic_shortcode_globalsettings
    WHERE shortcode_name = %s AND is_editable = '1'
EOD;

    $res = ajaxds_doSql($sqlQuery, array($shortcodeName), 'row');

    if (isset($res) && strval($res->shortcode_name) === strval($shortcodeName)) {
        $sqlQuery2 = <<<EOD
        SELECT shortcode_name
        FROM wp_dynamic_shortcode_settings
        WHERE shortcode_name = %s
    EOD;
    
        $res2 = ajaxds_doSql($sqlQuery2, array($shortcodeName), 'row');

        if (isset($res2) && strval($res2->shortcode_name) === strval($shortcodeName)){
            return 1;
        }
        
        return 0;
    }

    return 0;
}


/** Get the session data from raw string */
function ajaxds_getDataFromString($strVal)
{
    $strVal = gzinflate(base64_decode($strVal));
    $arrRet = array();
    $arrRetPre = explode('[_WP_D_S_DEL_]', $strVal);
    foreach ($arrRetPre as $keyValPair) {
        $explo = explode('[_WP_D_S_KEYVAL_]', $keyValPair);
        $arrRet = $arrRet + array($explo[0] => $explo[1]);
    }
    return $arrRet;
}


/** Set the session variables from the session ID */
function ajaxds_setSessionVars($sessId, $arrKeyVal, $isUpdate, $getPostParams)
{
    $strFin = '';
    $currUrl = ajaxds_getCurrentUrl();
    foreach ($arrKeyVal as $key => $val) {
        $strFin = $strFin . $key . '[_WP_D_S_KEYVAL_]' . $val . '[_WP_D_S_DEL_]';
    }
    $strFin = ajaxds_str_lreplace('[_WP_D_S_DEL_]', '', $strFin);
    $strFin = base64_encode(gzdeflate($strFin, 9));

    $dataDb = '';
    if (is_array($getPostParams) && count($getPostParams) > 0){
        $dataDb = ajaxds_safeEncodeBase64(json_encode($getPostParams));
    }

    if ($isUpdate) {
        $sqlQuery = <<<EOD
        UPDATE wp_dynamic_shortcode
        SET data = %s, expiration_date = expiration_date, current_url=%s, get_post_params=%s
        WHERE id_session = %s
EOD;
        $res = ajaxds_doSql($sqlQuery, array($strFin, $currUrl, $dataDb, $sessId), 'query');
    } else {
        $sessId = ajaxds_GUID();
        $sqlQuery = <<<EOD
        INSERT INTO wp_dynamic_shortcode (id_session, data, get_post_params, expiration_date, current_url)
        VALUES (%s, %s, %s, DATE(DATE_ADD(NOW(), INTERVAL +2 DAY)), %s)
EOD;
        $res = ajaxds_doSql($sqlQuery, array($sessId, $strFin, $dataDb, $currUrl), 'query');
    }

    $_SESSION['wp_dynamic_shortcode_usr_SESSID'] = $sessId;
}



/** Do an sql query and return the result 
 * @param String $sqlQuery The query string
 * @param array $arrayParams Array of parameters to replace in query string
 * @param String $typeQuery The type of query. Either 'results', 'row', 'var' or 'query'.
 */
function ajaxds_doSql($sqlQuery, $arrayParams, $typeQuery)
{
    global $wpdb;
    $res = null;
    if ($arrayParams !== null && count($arrayParams) > 0) {
        $sqlQuery = $wpdb->prepare($sqlQuery, $arrayParams);
    }

    if ($typeQuery === 'results') {
        $res = $wpdb->get_results($sqlQuery);
    } else if ($typeQuery === 'row') {
        $res = $wpdb->get_row($sqlQuery);
    } else if ($typeQuery === 'var') {
        $res = $wpdb->get_var($sqlQuery);
    } else if ($typeQuery === 'query') {
        $res = $wpdb->query($sqlQuery);
    }

    return $res;
}


function ajaxds_getShortcodeParameters($shortcodeName)
{
    return ajaxds_doSql("SELECT * FROM wp_dynamic_shortcode_settings WHERE shortcode_name = %s", array($shortcodeName), 'row');
}


function ajaxds_getIfShortcodeExist($shortcodeName)
{
    $res = ajaxds_doSql("SELECT shortcode_name FROM wp_dynamic_shortcode_settings WHERE shortcode_name = %s", array($shortcodeName), 'row')->shortcode_name === $shortcodeName;
    return $res;
}

function ajaxds_getIfPlaceholderExist($placeholderName)
{
    $res = ajaxds_doSql("SELECT placeholder_name FROM wp_dynamic_shortcode_placeholders WHERE placeholder_name = %s", array($placeholderName), 'row')->placeholder_name === $placeholderName;
    return $res;
}

function ajaxds_getAllPlaceholders($isEdit = false)
{
    if (!$isEdit) {
        return ajaxds_doSql("SELECT placeholder_name FROM wp_dynamic_shortcode_placeholders", null, 'results');
    } else {
        return ajaxds_doSql("SELECT placeholder_name FROM wp_dynamic_shortcode_placeholders WHERE placeholder_name != 'default'", null, 'results');
    }
}

function ajaxds_getPlaceholderValue($palceholderName)
{
    return ajaxds_doSql("SELECT data FROM wp_dynamic_shortcode_placeholders WHERE placeholder_name = %s", array($palceholderName), 'var');
}


function ajaxds_updateInsertShortcodeSettings(
    $shortcodeName,
    $enableDynamicReplace,
    $ignoreAttributesParameters,
    $ignoreGetParameters,
    $ignorePostParameters,
    $isJavascriptVariable,
    $placeholderName,
    $validationFunction,
    $isUpdate
) {
    $res = null;

    if ($isUpdate) {
        $res = ajaxds_doSql(
            "SELECT shortcode_name 
                           FROM wp_dynamic_shortcode_settings
                           WHERE shortcode_name = %s AND placeholder_name = %s AND 
                                 is_javascript_variable = %s AND get_parameters_ignore = %s AND
                                 post_parameters_ignore = %s AND validation_function_name = %s AND
                                 attributes_ignore = %s AND use_dynamic_replace = %s",
            array(
                $shortcodeName, $placeholderName, $isJavascriptVariable, $ignoreGetParameters,
                $ignorePostParameters, $validationFunction, $ignoreAttributesParameters,
                $enableDynamicReplace
            ),
            'row'
        );

        if (!isset($res->shortcode_name)) {
            $sql = "UPDATE wp_dynamic_shortcode_settings
            SET placeholder_name = %s,
                is_javascript_variable = %s,
                get_parameters_ignore = %s,
                post_parameters_ignore = %s,
                validation_function_name = %s,
                attributes_ignore = %s,
                use_dynamic_replace = %s
            WHERE shortcode_name = %s";

            $res = ajaxds_doSql($sql, array(
                $placeholderName, $isJavascriptVariable, $ignoreGetParameters,
                $ignorePostParameters, $validationFunction, $ignoreAttributesParameters,
                $enableDynamicReplace, $shortcodeName
            ), 'query');
        } else {
            $res = 1;
        }
    } else {
        $sql = "INSERT INTO wp_dynamic_shortcode_settings (shortcode_name, placeholder_name, is_javascript_variable, get_parameters_ignore,
                                                            post_parameters_ignore, validation_function_name, attributes_ignore, use_dynamic_replace)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s);";

        $res = ajaxds_doSql($sql, array(
            $shortcodeName, $placeholderName, $isJavascriptVariable, $ignoreGetParameters,
            $ignorePostParameters, $validationFunction, $ignoreAttributesParameters,
            $enableDynamicReplace
        ), 'query');
    }

    if ($res === 0) {
        return 0;
    } else {
        return 1;
    }
}



function ajaxds_updateInsertPlaceholderSettings(
    $placeholderName,
    $data,
    $isUpdate
) {
    $res = null;

    $data = '<div id="wp_dynamic_shortcode_[_MAIN_SHORTCODE_]_Loader">' . $data . '</div>';

    if ($isUpdate) {
        $res = ajaxds_doSql(
            "SELECT placeholder_name 
                           FROM wp_dynamic_shortcode_placeholders
                           WHERE placeholder_name = %s AND data = %s",
            array($placeholderName, $data),
            'row'
        );

        if (!isset($res->placeholder_name)) {
            $sql = "UPDATE wp_dynamic_shortcode_placeholders
            SET data = %s
            WHERE placeholder_name = %s";

            $res = ajaxds_doSql($sql, array($data, $placeholderName), 'query');
        } else {
            $res = 1;
        }
    } else {
        $sql = "INSERT INTO wp_dynamic_shortcode_placeholders (placeholder_name, data)
                VALUES (%s, %s);";

        $res = ajaxds_doSql($sql, array($placeholderName, $data), 'query');
    }

    if ($res === 0) {
        return 0;
    } else {
        return 1;
    }
}

function ajaxds_deletePlaceholder($placeholderName)
{
    $res = null;

    $res = ajaxds_doSql("DELETE FROM wp_dynamic_shortcode_placeholders 
    WHERE placeholder_name = %s", array($placeholderName), 'row');

    if ($res === 0) {
        return 0;
    } else {
        return 1;
    }
}



function ajaxds_LoadShortcodeSettings($shortcodeName)
{
    $sql = "SELECT * FROM wp_dynamic_shortcode_settings WHERE shortcode_name = %s";
    $res = ajaxds_doSql($sql, array($shortcodeName), 'row');

    return $res;
}


function ajaxds_LoadPlaceholderSettings($placeholderName)
{
    $sql = "SELECT * FROM wp_dynamic_shortcode_placeholders WHERE placeholder_name = %s";
    $res = ajaxds_doSql($sql, array($placeholderName), 'row');

    $fieldVal = "";
    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = false;
    $dom->loadHTML('<html><head></head><body>' . $res->data . '</body></html>');
    $xpath = new DOMXPath($dom);
    $tags = $xpath->query('.//div[@id="wp_dynamic_shortcode_[_MAIN_SHORTCODE_]_Loader"]');

    foreach ($tags as $tag) {
        $fieldVal = ajaxds_getInnerHTML($tag);
        break;
    }

    $res->data = $fieldVal;

    return $res;
}


function ajaxds_getDefaultParametersShortcode()
{
    return array(
        "placeholder_name" => "default",
        "is_javascript_variable" => "0",
        "get_parameters_ignore" => "",
        "post_parameters_ignore" => "",
        "validation_function_name" => "",
        "attributes_ignore" => "",
        "use_dynamic_replace" => "0"
    );
}


function ajaxds_getIfShortcodeEditableGlobalSettings($shortcodeName)
{
    $sql = "SELECT shortcode_name FROM wp_dynamic_shortcode_globalsettings WHERE setting_type = 'editable_shortcode' AND shortcode_name = %s";
    $res = ajaxds_doSql($sql, array($shortcodeName), 'row');
    return isset($res->shortcode_name) && $res->shortcode_name === $shortcodeName;
}

function ajaxds_getIfShortcodeEditableGlobalSettingsTrueFalse($shortcodeName)
{
    $sql = "SELECT is_editable FROM wp_dynamic_shortcode_globalsettings WHERE setting_type = 'editable_shortcode' AND shortcode_name = %s AND is_editable = '1'";
    $res = ajaxds_doSql($sql, array($shortcodeName), 'var');
    return strval($res) === '1';
}


function ajaxds_getAllNonSpecifiedEditableShortcodesGlobalSettings()
{
    $shortcodeRet = array();
    $shortcodes = ajaxds_getAllShortcodes();
    foreach ($shortcodes as $code => $function) {
        $isEditable = ajaxds_getIfShortcodeEditableGlobalSettings($code);
        if (!$isEditable) {
            array_push($shortcodeRet, $code);
        }
    }

    return $shortcodeRet;
}

/** Update or insert a global settings (is_editable is the value to set) */
function ajaxds_updateInsertGlobalSettings(
    $shortcodeName,
    $isEditable,
    $settingType,
    $isUpdate
) {
    $res = null;

    $isEditable = strval($isEditable) === 'on' ? '1' : '0';

    if ($isUpdate) {
        $res = ajaxds_doSql(
            "SELECT shortcode_name 
                           FROM wp_dynamic_shortcode_globalsettings
                           WHERE shortcode_name = %s AND setting_type = %s AND is_editable = %s",
            array($shortcodeName, $settingType, $isEditable),
            'row'
        );

        if (!isset($res->shortcode_name)) {
            $sql = "UPDATE wp_dynamic_shortcode_globalsettings
            SET is_editable = %s
            WHERE shortcode_name = %s AND setting_type = %s";

            $res = ajaxds_doSql($sql, array($isEditable, $shortcodeName, $settingType), 'query');
        } else {
            $res = 1;
        }
    } else {
        $sql = "INSERT INTO wp_dynamic_shortcode_globalsettings (shortcode_name, setting_type, is_editable)
                VALUES (%s, %s, %s);";

        $res = ajaxds_doSql($sql, array($shortcodeName, $settingType, $isEditable), 'query');
    }

    if ($res === 0) {
        return 0;
    } else {
        return 1;
    }
}






/** Get the post and get parameters from a sessionId */
function ajaxds_getPostGetParamsSession($sessionId)
{
    $res = ajaxds_doSql(
        "SELECT id_session, get_post_params
                       FROM wp_dynamic_shortcode
                       WHERE id_session = %s",
        array($sessionId),
        'row'
    );

    if (!isset($res->id_session)) {
        $res = 0;
    } else {
        $arrPost = array();
        $arrGet = array();

        if (isset($res->get_post_params) && strlen(strval($res->get_post_params)) > 0) {

            $res = json_decode(ajaxds_safeDecodeBase64($res->get_post_params));

            foreach ($res as $key => $val) {

                if (strpos($key, 'wp_dynamic_GETPARAM_') !== false) {
                    $realKey = str_replace('wp_dynamic_GETPARAM_', '', $key);
                    $arrGet[$realKey] = ajaxds_safeDecodeBase64($val);
                } else if (strpos($key, 'wp_dynamic_POSTPARAM_') !== false) {
                    $realKey = str_replace('wp_dynamic_POSTPARAM_', '', $key);
                    $arrPost[$realKey] = ajaxds_safeDecodeBase64($val);
                }
            }
        }

        $res = array('post' => $arrPost, 'get' => $arrGet);
    }

    return $res;
}


/** Get all shortcode that have Dynamic Replace enabled */
function ajaxds_getAllDynamicReplace()
{
    $sql = "SELECT shortcode_name FROM wp_dynamic_shortcode_settings WHERE use_dynamic_replace = '1'";
    $res = ajaxds_doSql($sql, null, 'results');
    return $res;
}
