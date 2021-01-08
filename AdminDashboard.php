<?php

function ajaxds_admin_menu()
{
    $page = add_menu_page(
        __('Dashboard', 'dynamic-shortcode-shortcodes'),
        __('Shortcodes', 'dynamic-shortcode-shortcodes'),
        'manage_options',
        'dynamic-shortcode-shortcodes',
        'ajaxds_admin_page_content_shortcodes',
        AJAXDS_PATH . 'resources/imgs/DynamicShortcode_Logo_Thumbnail_Admin-e1607698837951.jpg',
        3
    );

    add_submenu_page('dynamic-shortcode-shortcodes', 'Placeholders', 'Placeholders', 'manage_options', 'dynamic-shortcode-placeholders', 'ajaxds_admin_page_content_placeholders');
    add_submenu_page('dynamic-shortcode-shortcodes', 'Global Settings', 'Global Settings', 'manage_options', 'dynamic-shortcode-globalsettings', 'ajaxds_admin_page_content_globalsettings');
    add_submenu_page('dynamic-shortcode-shortcodes', 'Help', 'Help', 'manage_options', 'dynamic-shortcode-help', 'ajaxds_admin_page_content_help');
    add_submenu_page('dynamic-shortcode-shortcodes', 'Keep It Alive', 'Keep It Alive', 'manage_options', 'dynamic-shortcode-donate', 'ajaxds_admin_page_content_donate');
    add_submenu_page('dynamic-shortcode-shortcodes', 'Notice', 'Notice', 'manage_options', 'dynamic-shortcode-notice', 'ajaxds_admin_page_content_notice');
}

add_action('admin_menu', 'ajaxds_admin_menu');



function ajaxds_enqueuing_admin_scripts()
{
    wp_enqueue_script('ajaxds_sweetalert2', AJAXDS_PATH . 'resources/js/sweetalert2.js', array(), '1.0.0', true);
    wp_enqueue_style('ajaxds_bootstrap.min', AJAXDS_PATH . 'resources/css/bootstrap.min.css');
    wp_enqueue_style('ajaxds_Bootstrap-DataTables', AJAXDS_PATH . 'resources/css/Bootstrap-DataTables.css');
    wp_enqueue_style('ajaxds_Dark-NavBar-1', AJAXDS_PATH . 'resources/css/Dark-NavBar-1.css');
    wp_enqueue_style('ajaxds_Dark-NavBar-2', AJAXDS_PATH . 'resources/css/Dark-NavBar-2.css');
    wp_enqueue_style('ajaxds_Dark-NavBar', AJAXDS_PATH . 'resources/css/Dark-NavBar.css');
    wp_enqueue_style('ajaxds_dropdown-search-bs4', AJAXDS_PATH . 'resources/css/dropdown-search-bs4.css');
    wp_enqueue_style('ajaxds_dataTables.bootstrap4.min', AJAXDS_PATH . 'resources/css/dataTables.bootstrap4.min.css');
    wp_enqueue_style('ajaxds_styles',  AJAXDS_PATH . "resources/css/styles.css");
    wp_enqueue_script('ajaxds_bootstrap.bundle.min', AJAXDS_PATH . 'resources/js/bootstrap.bundle.min.js', array(), '1.0.0', true);
    wp_enqueue_script('ajaxds_jquery.dataTables.min', AJAXDS_PATH . 'resources/js/jquery.dataTables.min.js', array(), '1.0.0', true);
    wp_enqueue_script('ajaxds_dataTables.bootstrap4.min', AJAXDS_PATH . 'resources/js/dataTables.bootstrap4.min.js', array(), '1.0.0', true);
}

add_action('admin_enqueue_scripts', 'ajaxds_enqueuing_admin_scripts');



/** Page content of the help page */
function ajaxds_admin_page_content_notice()
{
?>
    <script>
        var ajaxds_mainIpAddress = '<?php echo ajaxds_getVisitorIp(); ?>';
        var ajaxds_mainUserId = '<?php echo wp_get_current_user()->ID; ?>';
    </script>

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid rgb(52, 58, 64);
            width: 120px;
            height: 120px;
            -webkit-animation: spin 1s linear infinite;
            /* Safari */
            animation: spin 1s linear infinite;
            margin: auto;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .custom-control,
        .custom-checkbox {
            z-index: 0 !important;
        }
    </style>

    <div></div>

    <div id="loaderContainer" style="display:none;position:fixed;width:100vw;height:100vh;background-color:#00000069;text-align:center;margin:auto;z-index: 999999;">
        <div id="loaderSubContainer" style="position: absolute;
            top: 40%;
            left: 50%;
            transform: translateX(-50%);">
            <div class="loader"></div>
        </div>
    </div>

    <?php echo ajaxds_getNavbar(); ?>

    <div>
        <div class="container">
            <div class="row">
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertSuccess" class="alert alert-success"></div>
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertDanger" class="alert alert-danger"></div>
                <div class="col-md-12" style="text-align: center;">
                    <h1 style="margin: auto;margin-top: 20px;margin-bottom: 80px;">Notice</h1>
                </div>
            </div>
        </div>
    </div>
    <div>

        <form id="form_data" onsubmit="return false;">
            <div class="" style="max-width:90%;margin:auto;">
                <div class="row" style="margin-bottom: 100px;">
                    <div class="col">
                        <div style="text-align: center;">
                            <ul class="list-unstyled" style="width: 60%;margin: auto;">
                                <li>
                                    Dynamic Shortcode act as a black box proxy: It takes the very state of the request while it tries to call the original shortcode, saves it, 
                                    then reload it when the AJAX call is made to execute the original shortcode. Since Dynamic Shortcode is a black box proxy, it <b>does not</b> alter
                                    the original request in any way. While make sure that no PHP remote code execution occur while the parameters are sent through the Dynamic Shortcode proxy,
                                    it is <b>your responsibility</b> to validate, escape and sanitize any parameter sent to your shortcode. It is also your <b>your responsibility</b> 
                                    to make sure that the web request is legitimate.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        #wpcontent {
            padding-left: 0px !important;
        }
    </style>
<?php
}




function ajaxds_admin_page_content_donate()
{
?>
    <script>
        var ajaxds_mainIpAddress = '<?php echo ajaxds_getVisitorIp(); ?>';
        var ajaxds_mainUserId = '<?php echo wp_get_current_user()->ID; ?>';
    </script>

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid rgb(52, 58, 64);
            width: 120px;
            height: 120px;
            -webkit-animation: spin 1s linear infinite;
            /* Safari */
            animation: spin 1s linear infinite;
            margin: auto;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .custom-control,
        .custom-checkbox {
            z-index: 0 !important;
        }
    </style>

    <div></div>

    <div id="loaderContainer" style="display:none;position:fixed;width:100vw;height:100vh;background-color:#00000069;text-align:center;margin:auto;z-index: 999999;">
        <div id="loaderSubContainer" style="position: absolute;
            top: 40%;
            left: 50%;
            transform: translateX(-50%);">
            <div class="loader"></div>
        </div>
    </div>

    <?php echo ajaxds_getNavbar(); ?>

    <div>
        <div class="container">
            <div class="row">
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertSuccess" class="alert alert-success"></div>
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertDanger" class="alert alert-danger"></div>
                <div class="col-md-12" style="text-align: center;">
                    <h1 style="margin: auto;margin-top: 20px;margin-bottom: 80px;">You're The Beating Heart Of Dynamic Shortcode</h1>
                </div>
            </div>
        </div>
    </div>
    <div>


        <div class="" style="max-width:90%;margin:auto;">
            <div class="row" style="margin-bottom: 100px;">
                <div class="col">
                    <div style="text-align: center;">
                        <h5 style="text-align: center;margin-bottom: 60px;line-height:30px;">
                            <b style="font-size: 24px;">We hate ads and recuring payments as much as you do.</b><br><br><br><br>
                            In order to keep Dynamic Shortcode free and actively developed, <b>we rely on your goodwill</b>.
                            Any amount is greatly appreciated and help us to improve Dynamic Shortcode by adding new features and keeping it up-to-date!<br><br><br>
                            <b>Note:</b> If you wish to give us an amount greater than 50$, you can <b>propose us a feature</b> in the donation note section. We will evaluate it and try to add it in the next update!
                        </h5>
                        <div style="width: 60%;margin: auto;">
                            <form action="https://www.paypal.com/donate" method="post" target="_blank">
                                <input type="hidden" name="hosted_button_id" value="9PG7333GR22ZA" />
                                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                                <img alt="" border="0" src="https://www.paypal.com/en_CA/i/scr/pixel.gif" width="1" height="1" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <style>
        #wpcontent {
            padding-left: 0px !important;
        }
    </style>
<?php
}



/** Page content of the help page */
function ajaxds_admin_page_content_help()
{
?>
    <script>
        var ajaxds_mainIpAddress = '<?php echo ajaxds_getVisitorIp(); ?>';
        var ajaxds_mainUserId = '<?php echo wp_get_current_user()->ID; ?>';
    </script>

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid rgb(52, 58, 64);
            width: 120px;
            height: 120px;
            -webkit-animation: spin 1s linear infinite;
            /* Safari */
            animation: spin 1s linear infinite;
            margin: auto;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .custom-control,
        .custom-checkbox {
            z-index: 0 !important;
        }
    </style>

    <div></div>

    <div id="loaderContainer" style="display:none;position:fixed;width:100vw;height:100vh;background-color:#00000069;text-align:center;margin:auto;z-index: 999999;">
        <div id="loaderSubContainer" style="position: absolute;
            top: 40%;
            left: 50%;
            transform: translateX(-50%);">
            <div class="loader"></div>
        </div>
    </div>

    <?php echo ajaxds_getNavbar(); ?>

    <div>
        <div class="container">
            <div class="row">
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertSuccess" class="alert alert-success"></div>
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertDanger" class="alert alert-danger"></div>
                <div class="col-md-12" style="text-align: center;">
                    <h1 style="margin: auto;margin-top: 20px;margin-bottom: 80px;">How To Use Dynamic Shortcode</h1>
                </div>
            </div>
        </div>
    </div>
    <div>

        <form id="form_data" onsubmit="return false;">
            <div class="" style="max-width:90%;margin:auto;">
                <div class="row" style="margin-bottom: 100px;">
                    <div class="col">
                        <div style="text-align: center;">
                            <h5 style="text-align: center;margin-bottom: 60px;">First of all, go in the "Global Settings" tab and enable<br/>the shortcodes that Dynamic Shortcode can use.<br/>Once this is done:</h5>
                            <h6 style="text-align: center;margin-bottom: 60px;">Edit your shortcode settings in the "Shortcodes" tab.<br/>If you have "Dynamic Replace" enabled in your shortcode's settings,<br>you don't have anything else to do. Otherwise:</h6>
                            <ul class="list-unstyled" style="width: 60%;margin: auto;">
                                <li>Find an occurence of your shortcode, and replace your shortcode's name with <strong>wp_dynamic</strong>&nbsp;and add a parameter named&nbsp;<strong>shortcode</strong>&nbsp;with your shortcode's name as a value. For example:<br><br><strong>[myShortcode]<br></strong><br>becomes<br><br><strong>[wp_dynamic shortcode="myShortcode"]<br></strong><br></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        #wpcontent {
            padding-left: 0px !important;
        }
    </style>
<?php
}



/** Page content of the global settings page */
function ajaxds_admin_page_content_globalsettings()
{
?>
    <script>
        var ajaxds_mainIpAddress = '<?php echo ajaxds_getVisitorIp(); ?>';
        var ajaxds_mainUserId = '<?php echo wp_get_current_user()->ID; ?>';
        var ajaxds_mainNonce = '<?php echo wp_create_nonce('ajaxds_ajaxSaveGlobalSettingsPlaceholder_' . strval(ajaxds_getVisitorIp()) . '_' . strval(wp_get_current_user()->ID)); ?>';
    </script>


    <script>
        <?php echo ajaxds_getJsSaveGlobalSettings(); ?>
        <?php echo ajaxds_getJsToggleAll(); ?>
    </script>

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid rgb(52, 58, 64);
            width: 120px;
            height: 120px;
            -webkit-animation: spin 1s linear infinite;
            /* Safari */
            animation: spin 1s linear infinite;
            margin: auto;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .custom-control,
        .custom-checkbox {
            z-index: 0 !important;
        }
    </style>

    <div></div>

    <div id="loaderContainer" style="display:none;position:fixed;width:100vw;height:100vh;background-color:#00000069;text-align:center;margin:auto;z-index: 999999;">
        <div id="loaderSubContainer" style="position: absolute;
            top: 40%;
            left: 50%;
            transform: translateX(-50%);">
            <div class="loader"></div>
        </div>
    </div>

    <?php echo ajaxds_getNavbar(); ?>

    <div>
        <div class="container">
            <div class="row">
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertSuccess" class="alert alert-success"></div>
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertDanger" class="alert alert-danger"></div>
                <div class="col-md-12" style="text-align: center;">
                    <h1 style="margin: auto;margin-top: 20px;margin-bottom: 80px;">Global Settings</h1>
                </div>
            </div>
        </div>
    </div>
    <div>



        <form id="form_data" onsubmit="return false;">
            <div class="" style="max-width:90%;margin:auto;">
                <div class="row" style="margin-bottom: 100px;">
                    <div class="col">
                        <div style="text-align: center;">
                            <h5 style="text-align: center;margin-bottom: 20px;margin-top: 40px;">Editable Shortcodes Settings</h5>
                            <small class="form-text text-muted" style="margin-bottom: 5px;">
                                Check every shortcode you want to appear in the edit dropdown<br />
                                <b>IMPORTANT NOTE: Dynamic Shortcode will only be able to use the checked shortcodes below.</b><br />
                                <b>If a shortcode is not checked and you try to use it with Dynamic Shortcode, an error will be thrown.</b><br />
                                <b>This feature is implemented for security reasons, to prevent attackers from calling unwanted shortcodes.</b>
                            </small>

                            <div class="custom-control custom-checkbox" style="margin-bottom: 20px;margin-top:20px;">
                                <input type="checkbox" id="toggleAll" class="custom-control-input">
                                <label class="custom-control-label" for="toggleAll">Toggle All</label>
                            </div>

                            <ul class="list-unstyled text-left" style="margin: auto;width: 30%;min-width: 350px;">
                                <?php echo ajaxds_getAllShortcodesHtmlCheckbox(); ?>
                            </ul>
                            <script>
                                <?php echo ajaxds_getJsInitCheck(); ?>
                            </script>
                            <button class="btn btn-primary" type="button" style="margin-top: 60px;" onclick="ajaxds_SaveSettingsGlobal();">Save Global Settings</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {


            var rad = document.getElementsByName("customRadioInline");
            var prev = null;
            for (var i = 0; i < rad.length; i++) {
                rad[i].addEventListener('change', function() {
                    if (this !== prev) {
                        prev = this;
                    }
                    ajaxds_toggleRadio(this.value);
                });
            }


        });

        function ajaxds_toggleRadio(val) {
            var editEl = document.getElementById('editPladeholderId');
            var createEl = document.getElementById('createPladeholderId');
            var deletePlaceholder = document.getElementById('deletePlaceholder');

            deletePlaceholder.style.display = "none";
            if (val === "edit") {
                editEl.querySelector('button').innerHTML = "Pick Your Placeholder";
                createEl.style.display = "none";
                editEl.style.display = "block";
            } else if (val === "create") {
                document.getElementById('placeholderName').value = "";
                document.getElementById('data').value = "";
                editEl.style.display = "none";
                createEl.style.display = "block";
            }
        }
    </script>
    <style>
        #wpcontent {
            padding-left: 0px !important;
        }
    </style>
<?php
}


function ajaxds_validateGenuineRequest($noncePrefix)
{
    $ip = ajaxds_getVisitorIp();
    check_ajax_referer($noncePrefix . strval($ip) . '_' . ajaxds_sanitizeBasicTextInput($_POST['userId']), 'nonce');

    if (strval($ip) !== ajaxds_sanitizeBasicTextInput($_POST['ipAddress'])) {
        wp_send_json(array("success" => false, "data" => "Invalid IP Address."));
    }
}



/** AJAX that save the global settings */
function ajaxds_ajax_dynamic_shortcode_save_globalsettings()
{
    ajaxds_validateGenuineRequest('ajaxds_ajaxSaveGlobalSettingsPlaceholder_');

    $counter = 0;
    $res = 0;
    if (is_array($_POST)) {
        $POSTKeys = array_keys($_POST);
        foreach ($POSTKeys as $key) {
            if (substr(strval($key), 0, 10) === 'chkToggle_' && isset($_POST[$key])) {
                $val = ajaxds_sanitizeBasicTextInput($_POST[$key]);
                $counter = $counter + 1;
                $newKey = str_replace('chkToggle_', '', $key);
                $exist = ajaxds_getIfShortcodeEditableGlobalSettings($newKey);
                if ($exist) {
                    $res = ajaxds_updateInsertGlobalSettings(
                        $newKey,
                        $val,
                        'editable_shortcode',
                        true
                    );
                } else {
                    $res = ajaxds_updateInsertGlobalSettings(
                        $newKey,
                        $val,
                        'editable_shortcode',
                        false
                    );
                }
            }
        }
    }

    if ($res === 0) {
        wp_send_json(array("success" => false, "data" => "An error occured while saving your global settings."));
    } else {
        wp_send_json(array("success" => true, "data" => "Global settings saved!"));
    }
}
add_action('wp_ajax_dynamic_shortcode_save_globalsettings', 'ajaxds_ajax_dynamic_shortcode_save_globalsettings');
add_action('wp_ajax_nopriv_dynamic_shortcode_save_globalsettings', 'ajaxds_ajax_dynamic_shortcode_save_globalsettings');



function ajaxds_admin_page_content_placeholders()
{
    $placeholdersOutput = ajaxds_getAllPlaceholdersHtml(true);
?>
    <script>
        var ajaxds_mainIpAddress = '<?php echo ajaxds_getVisitorIp(); ?>';
        var ajaxds_mainUserId = '<?php echo wp_get_current_user()->ID; ?>';
        var ajaxds_mainNonce = '<?php echo wp_create_nonce('ajaxds_ajaxNonceSaveSettingsPlaceholder_' . strval(ajaxds_getVisitorIp()) . '_' . strval(wp_get_current_user()->ID)); ?>';
        var ajaxds_loadNonce = '<?php echo wp_create_nonce('ajaxds_ajaxNonceLoadSettingsPlaceholder_' . strval(ajaxds_getVisitorIp()) . '_' . strval(wp_get_current_user()->ID)); ?>';
        var ajaxds_deleteNonce = '<?php echo wp_create_nonce('ajaxds_ajaxNonceDeletePlaceholder_' . strval(ajaxds_getVisitorIp()) . '_' . strval(wp_get_current_user()->ID)); ?>';
    </script>

    <script>
        <?php echo ajaxds_getJsDeletePlaceholder(); ?>
        <?php echo ajaxds_getJsSaveSettingsPlaceholder(); ?>
        <?php echo ajaxds_getJsLoadSettingsPlaceholder(); ?>
    </script>

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid rgb(52, 58, 64);
            width: 120px;
            height: 120px;
            -webkit-animation: spin 1s linear infinite;
            /* Safari */
            animation: spin 1s linear infinite;
            margin: auto;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .custom-control,
        .custom-checkbox {
            z-index: 0 !important;
        }
    </style>

    <div></div>

    <div id="loaderContainer" style="display:none;position:fixed;width:100vw;height:100vh;background-color:#00000069;text-align:center;margin:auto;z-index: 999999;">
        <div id="loaderSubContainer" style="position: absolute;
        top: 40%;
        left: 50%;
        transform: translateX(-50%);">
            <div class="loader"></div>
        </div>
    </div>

    <?php echo ajaxds_getNavbar(); ?>

    <div>
        <div class="container">
            <div class="row">
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertSuccess" class="alert alert-success"></div>
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertDanger" class="alert alert-danger"></div>
                <div class="col-md-12" style="text-align: center;">
                    <h1 style="margin: auto;margin-top: 20px;margin-bottom: 80px;">Placeholders</h1>
                </div>
            </div>
        </div>
    </div>
    <div>



        <form id="form_data" onsubmit="return false;">
            <div class="" style="max-width:90%;margin:auto;">
                <div class="row" style="margin-bottom: 100px;">
                    <div class="col-md-4" id="pickYourShortcodeId" style="border-right: 2px solid rgb(218,218,218);text-align: center;">
                        <fieldset>
                            <div class="custom-control custom-radio custom-control-inline"><input type="radio" id="radioEditPlaceholder" class="custom-control-input" name="customRadioInline" checked="" value="edit"><label class="custom-control-label" for="radioEditPlaceholder">Edit a placeholder</label></div>
                            <div class="custom-control custom-radio custom-control-inline"><input type="radio" id="radioCreatePlaceholder" class="custom-control-input" name="customRadioInline" value="create"><label class="custom-control-label" for="radioCreatePlaceholder">Create a placeholder</label></div>
                        </fieldset>
                        <div class="row">
                            <div class="col" id="editPladeholderId" style="margin-top: 40px;margin-bottom: 40px;">
                                <h5 style="text-align: center;margin-bottom: 20px;">Placeholder To Edit:</h5>
                                <div class="dropdown" style="margin: auto;text-align: center;">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="background-color: rgb(52,58,64);margin: auto;border-color: black !important;font-size:14px;">Pick Your Placeholder</button>
                                    <div class="dropdown-menu" style="background-color: #1f2021;color: rgb(255,255,255);/*text-align: center;*//*margin: auto;*/">
                                        <?php echo $placeholdersOutput; ?>
                                    </div>
                                </div>
                                <div style="text-align: center;">
                                    <div></div><button class="btn btn-danger" id="deletePlaceholder" type="button" style="margin-top: 40px;display:none;" onclick="ajaxds_DeletePlaceholder();">Delete Placeholer</button>
                                </div>
                            </div>
                            <div class="col" id="createPladeholderId" style="margin-top: 40px;margin-bottom: 40px;display:none;">
                                <h5 style="text-align: center;margin-bottom: 20px;">New Placeholder Name</h5>
                                <input type="text" name="placeholderName" id="placeholderName">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div style="text-align: center;">
                            <h5 style="text-align: center;margin-bottom: 20px;margin-top: 40px;">Placeholder HTML Code</h5>
                            <small class="form-text text-muted" style="margin-bottom: 5px;">Paste your placeholder HTML code here. <br><b>IMPORTANT! You cannot use the keyword "[_MAIN_SHORTCODE_]".</b></small>
                            <textarea style="width: 300px;height: 100px;" id="data" name="data"></textarea>
                            <fieldset style="margin: auto;text-align: center;"></fieldset>
                        </div>
                        <div style="text-align: center;">
                            <div></div><button class="btn btn-primary" type="button" style="margin-top: 60px;" onclick="ajaxds_SaveSettingsPlaceholder();">Save Placeholer</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {


            var rad = document.getElementsByName("customRadioInline");
            var prev = null;
            for (var i = 0; i < rad.length; i++) {
                rad[i].addEventListener('change', function() {
                    if (this !== prev) {
                        prev = this;
                    }
                    ajaxds_toggleRadio(this.value);
                });
            }


        });

        function ajaxds_toggleRadio(val) {
            var editEl = document.getElementById('editPladeholderId');
            var createEl = document.getElementById('createPladeholderId');
            var deletePlaceholder = document.getElementById('deletePlaceholder');

            deletePlaceholder.style.display = "none";
            if (val === "edit") {
                editEl.querySelector('button').innerHTML = "Pick Your Placeholder";
                createEl.style.display = "none";
                editEl.style.display = "block";
            } else if (val === "create") {
                document.getElementById('placeholderName').value = "";
                document.getElementById('data').value = "";
                editEl.style.display = "none";
                createEl.style.display = "block";
            }
        }
    </script>
    <style>
        #wpcontent {
            padding-left: 0px !important;
        }
    </style>
<?php
}



/** Content of the admin panel page */
function ajaxds_admin_page_content_shortcodes()
{
    $shortcodesOutput = ajaxds_getAllShortcodesHtml();
    $placeholdersOutput = ajaxds_getAllPlaceholdersHtml();
?>
    <script>
        var ajaxds_mainIpAddress = '<?php echo ajaxds_getVisitorIp(); ?>';
        var ajaxds_mainUserId = '<?php echo wp_get_current_user()->ID; ?>';
        var ajaxds_mainNonce = '<?php echo wp_create_nonce('ajaxds_ajaxNonceSaveSettingsShortcode_' . strval(ajaxds_getVisitorIp()) . '_' . strval(wp_get_current_user()->ID)); ?>';
        var ajaxds_loadNonce = '<?php echo wp_create_nonce('ajaxds_ajaxNonceLoadSettingsShortcode_' . strval(ajaxds_getVisitorIp()) . '_' . strval(wp_get_current_user()->ID)); ?>';
    </script>

    <script>
        <?php echo ajaxds_getJsSaveSettingsShortcode(); ?>
        <?php echo ajaxds_getJsLoadSettingsShortcode(); ?>
    </script>


    <style>
        .dropbtn {
            background-color: rgb(52, 58, 64);
            color: white;
            /* padding: 16px; */
            padding-top: 6px;
            padding-bottom: 6px;
            padding-right: 12px;
            padding-left: 12px;
            margin: 1px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            border-radius: .25rem;
        }

        .dropbtn:hover,
        .dropbtn:focus {
            background-color: rgb(52, 58, 64);
        }

        #shortcodeDropdownSearchInput {
            box-sizing: border-box;
            /* background-image: url('searchicon.png'); */
            background-position: 14px 12px;
            background-repeat: no-repeat;
            font-size: 14px;
            padding: 14px 20px 12px 45px;
            border: none;
            border-bottom: 1px solid #ddd;
            width: 100%;
        }

        #shortcodeDropdownSearchInput:focus {
            outline: 3px solid #006799;
        }

        .dropdown-custom {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: rgb(31, 32, 33);
            min-width: 230px;
            overflow: auto;
            border: 1px solid #006799;
            z-index: 1;
            text-align: left;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-custom a:hover {
            background-color: rgb(54, 55, 57);
        }


        .dropbtn::after {
            display: inline-block;
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            border-top: .3em solid;
            border-right: .3em solid transparent;
            border-bottom: 0;
            border-left: .3em solid transparent;
        }

        .show {
            display: block;
        }

        .btn-primary.focus,
        .btn-primary:focus {
            border-color: transparent !important;
            box-shadow: none !important;
        }

        .dropdown-item {
            font-size: 14px;
        }
    </style>


    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid rgb(52, 58, 64);
            width: 120px;
            height: 120px;
            -webkit-animation: spin 1s linear infinite;
            /* Safari */
            animation: spin 1s linear infinite;
            margin: auto;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .custom-control,
        .custom-checkbox {
            z-index: 0 !important;
        }
    </style>


    <script>
        function ajaxds_filterFunctionShortcode() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("shortcodeDropdownSearchInput");
            filter = input.value.toUpperCase();
            div = document.getElementById("shortcodeDropdown");
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                txtValue = a[i].textContent || a[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }
    </script>

    <div></div>

    <div id="loaderContainer" style="display:none;position:fixed;width:100vw;height:100vh;background-color:#00000069;text-align:center;margin:auto;z-index: 999999;">
        <div id="loaderSubContainer" style="position: absolute;
    top: 40%;
    left: 50%;
    transform: translateX(-50%);">
            <div class="loader"></div>
        </div>
    </div>

    <?php echo ajaxds_getNavbar(); ?>

    <div>
        <div class="container">
            <div class="row">
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertSuccess" class="alert alert-success"></div>
                <div role="alert" style="width:80% !important;text-align: center;margin: auto;margin-top: 20px;display:none;" id="alertDanger" class="alert alert-danger"></div>
                <div class="col-md-12" style="text-align: center;">
                    <h1 style="margin: auto;margin-top: 20px;margin-bottom: 80px;">Shortcodes</h1>
                </div>
            </div>
        </div>
    </div>
    <div>



        <form id="form_data" onsubmit="return false;">
            <!-- container -->
            <div class="" style="max-width:90%;margin:auto;">
                <div class="row" style="margin-bottom: 100px;">
                    <div class="col-md-6" id="pickYourShortcodeId" style="border-right: 2px solid rgb(218,218,218);text-align: center;margin-bottom: 40px;">
                        <h5 style="text-align: center;margin-bottom: 20px;">Shortcode to edit:</h5>
                        <input type="hidden" name="shortcodeName" id="shortcodeName" />

                        <div class="dropdown-custom">
                            <button onclick="document.getElementById('shortcodeDropdown').classList.toggle('show')" class="dropbtn">Pick Your Shortcode</button>
                            <div id="shortcodeDropdown" class="dropdown-content">
                                <input type="text" placeholder="Search.." id="shortcodeDropdownSearchInput" onkeyup="ajaxds_filterFunctionShortcode()">
                                <?php echo $shortcodesOutput; ?>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <h5 style="text-align: center;margin-bottom: 20px;">Placeholder to use when loading:</h5>
                        <input type="hidden" name="placeholderName" id="placeholderName" />
                        <div class="dropdown" style="margin: auto;text-align: center;">
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" style="background-color: rgb(52,58,64);margin: auto;border-color: black !important;font-size:14px;">Pick Your Placeholder</button>
                            <div class="dropdown-menu" style="background-color: #1f2021;color: rgb(255,255,255);/*text-align: center;*//*margin: auto;*/">
                                <?php echo $placeholdersOutput; ?>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <h5 style="text-align: center;margin-bottom: 20px;margin-top: 40px;">Javascript Settings</h5><small class="form-text text-muted" style="margin-bottom: 5px;text-align: center;">If your shortcode output a Javascript variable, you must check this.</small>
                            <fieldset style="margin: auto;text-align: center;">
                                <div class="custom-control custom-checkbox"><input type="checkbox" id="isJavascriptVariable" name="isJavascriptVariable" class="custom-control-input"><label class="custom-control-label" for="isJavascriptVariable">Is Javascript variable</label></div>
                            </fieldset>
                        </div>
                        <hr>
                        <div style="text-align: center;">
                            <h5 style="text-align: center;margin-bottom: 20px;margin-top: 40px;">GET Parameters Settings</h5><small class="form-text text-muted" style="margin-bottom: 5px;">Enter every GET parameters you want to ignore, one per line.&nbsp;<strong>This is case sentive!</strong></small><textarea style="width: 300px;height: 100px;" id="ignoreGetParameters" name="ignoreGetParameters"></textarea>
                            <fieldset style="margin: auto;text-align: center;"></fieldset>
                        </div>
                        <hr>
                        <div style="text-align: center;">
                            <h5 style="text-align: center;margin-bottom: 20px;margin-top: 40px;">POST Parameters Settings</h5><small class="form-text text-muted" style="margin-bottom: 5px;">Enter every POST parameters you want to ignore, one per line.&nbsp;<strong>This is case sentive!</strong></small><textarea style="width: 300px;height: 100px;" id="ignorePostParameters" name="ignorePostParameters"></textarea>
                            <fieldset style="margin: auto;text-align: center;"></fieldset>
                        </div>
                        <hr>
                        <div style="text-align: center;">
                            <h5 style="text-align: center;margin-bottom: 20px;margin-top: 40px;">Validation Function Settings</h5><small class="form-text text-muted" style="margin-bottom: 5px;">Enter the name of a validation function to call before executing the your shortcode.&nbsp;<strong>This is case sentive!</strong></small><input type="text" id="validationFunction" name="validationFunction">
                            <fieldset style="margin: auto;text-align: center;"></fieldset>
                        </div>
                        <hr>
                        <div style="text-align: center;">
                            <h5 style="text-align: center;margin-bottom: 20px;margin-top: 40px;">Attributes Settings</h5><small class="form-text text-muted" style="margin-bottom: 5px;">Enter every shortcode attribute you want to ignore, one per line.&nbsp;<strong>This is case sentive!</strong></small><textarea style="width: 300px;height: 100px;" id="ignoreAttributesParameters" name="ignoreAttributesParameters"></textarea>
                            <hr>
                            <div>
                                <h5 style="text-align: center;margin-bottom: 20px;margin-top: 40px;">Dynamic Replace Settings<span style="font-size: 10px;color: red;"> Beta</span></h5><small class="form-text text-muted" style="margin-bottom: 5px;text-align: center;">Find and replace this shortcode automatically, dynamically.</small><small class="form-text text-muted" style="margin-bottom: 5px;text-align: center;"><a href="/wp-admin/admin.php?page=dynamic-shortcode-help">Otherwise, you must&nbsp;manually change your shortcode call.<br></a></small>
                                <div class="custom-control custom-checkbox"><input type="checkbox" id="enableDynamicReplace" name="enableDynamicReplace" class="custom-control-input"><label class="custom-control-label" for="enableDynamicReplace">Enable Dynamic Replace<br></label></div>
                            </div>
                            <fieldset style="margin: auto;text-align: center;"></fieldset>
                        </div>
                        <div style="text-align: center;">
                            <div></div><button class="btn btn-primary" type="button" style="margin-top: 60px;" onclick="ajaxds_SaveSettingsShortcode();">Save Shortcode Settings</button>
                            <fieldset style="margin: auto;text-align: center;"></fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        #wpcontent {
            padding-left: 0px !important;
        }
    </style>
<?php
}



/** Get all the shortcodes in array format */
function ajaxds_getAllShortcodes($onlyEditable = false)
{
    global $shortcode_tags;
    $shortcodes = $shortcode_tags;
    ksort($shortcodes);

    if ($onlyEditable) {
        $shortcodesFinal = array();
        foreach ($shortcodes as $code => $function) {
            if (ajaxds_getIfShortcodeEditableGlobalSettingsTrueFalse($code)) {
                $shortcodesFinal[$code] = $function;
            }
        }

        $shortcodes = $shortcodesFinal;
    }

    return $shortcodes;
}



/** Get all shortcodes in HTML dropdown format (all "a" links that will go in the dropdown) */
function ajaxds_getAllShortcodesHtml()
{
    $shortcodes = ajaxds_getAllShortcodes(true);
    $shortcodesOutput = '';
    foreach ($shortcodes as $code => $function) {
        $shortcodesOutput .= '<a class="dropdown-item" href="Javascript:void(0);" style="color: rgb(255,255,255);" 
                                 onclick="document.getElementById(\'shortcodeName\').setAttribute(\'value\', this.innerHTML);
                                          this.parentElement.parentElement.querySelector(\'button\').innerHTML = this.innerHTML;
                                          document.getElementById(\'shortcodeDropdown\').classList.toggle(\'show\');ajaxds_ajaxLoadSettingsShortcode(this.innerHTML);">' . $code . '</a>';
    }
    return $shortcodesOutput;
}


/** Get all shortcodes in HTML checkbox format (For global settings)*/
function ajaxds_getAllShortcodesHtmlCheckbox()
{
    $shortcodes = ajaxds_getAllShortcodes();
    $shortcodesOutput = '';
    foreach ($shortcodes as $code => $function) {
        $isEditable = ajaxds_getIfShortcodeEditableGlobalSettingsTrueFalse($code) ? 'will-check="true"' : 'will-check="false"';
        $shortcodesOutput .= <<<EOD
        <li>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" id="chkToggle_$code" name="chkToggle_$code" shortcode-id="$code" class="custom-control-input" $isEditable>
                <label class="custom-control-label" for="chkToggle_$code">$code</label>
            </div>
        </li>
EOD;
    }
    return $shortcodesOutput;
}


/** Get all shortcodes in HTML dropdown format (all "a" links that will go in the dropdown) */
function ajaxds_getAllPlaceholdersHtml($isPlaceholderPage = false)
{
    $placeholders = ajaxds_getAllPlaceholders($isPlaceholderPage);
    $placeholdersOutput = '';
    foreach ($placeholders as $ph) {
        if ($isPlaceholderPage) {
            $placeholdersOutput .= '<a class="dropdown-item" href="Javascript:void(0);" style="color: rgb(255,255,255);" 
            onclick="document.getElementById(\'placeholderName\').setAttribute(\'value\', this.innerHTML);
                     this.parentElement.parentElement.querySelector(\'button\').innerHTML = this.innerHTML;ajaxds_ajaxLoadSettingsPlaceholder(this.innerHTML);">' . $ph->placeholder_name . '</a>';
        } else {
            $placeholdersOutput .= '<a class="dropdown-item" href="Javascript:void(0);" style="color: rgb(255,255,255);" 
            onclick="document.getElementById(\'placeholderName\').setAttribute(\'value\', this.innerHTML);
                     this.parentElement.parentElement.querySelector(\'button\').innerHTML = this.innerHTML;">' . $ph->placeholder_name . '</a>';
        }
    }
    return $placeholdersOutput;
}



/** AJAX that saves the selected shortcode settings */
function ajaxds_ajax_dynamic_shortcode_settings()
{
    ajaxds_validateGenuineRequest('ajaxds_ajaxNonceSaveSettingsShortcode_');

    $shortcodeName = ajaxds_sanitizeBasicTextInput($_POST['shortcodeName']) ?? "";
    $enableDynamicReplace = isset($_POST['enableDynamicReplace']) && ajaxds_sanitizeBasicTextInput($_POST['enableDynamicReplace']) === 'on' ? 1 : 0;
    $ignoreAttributesParameters = ajaxds_sanitizeBasicTextInput($_POST['ignoreAttributesParameters']) ?? "";
    $ignoreGetParameters = ajaxds_sanitizeBasicTextInput($_POST['ignoreGetParameters']) ?? "";
    $ignorePostParameters = ajaxds_sanitizeBasicTextInput($_POST['ignorePostParameters']) ?? "";
    $isJavascriptVariable = isset($_POST['isJavascriptVariable']) && ajaxds_sanitizeBasicTextInput($_POST['isJavascriptVariable']) === 'on' ? 1 : 0;
    $placeholderName = ajaxds_sanitizeBasicTextInput($_POST['placeholderName']) ?? "";
    $validationFunction = ajaxds_sanitizeBasicTextInput($_POST['validationFunction']) ?? "";

    // Validate the settings
    $errorMsg = ajaxds_validateShortcodeSettings(
        $shortcodeName,
        $ignoreAttributesParameters,
        $ignoreGetParameters,
        $ignorePostParameters,
        $placeholderName,
        $validationFunction
    );

    if (strlen($errorMsg) > 0) {
        wp_send_json(array("success" => false, "data" => $errorMsg));
    }

    // Insert or update the shortcode settings
    $isUpdate = ajaxds_getIfShortcodeExist($shortcodeName);

    $res = ajaxds_updateInsertShortcodeSettings(
        $shortcodeName,
        $enableDynamicReplace,
        $ignoreAttributesParameters,
        $ignoreGetParameters,
        $ignorePostParameters,
        $isJavascriptVariable,
        $placeholderName,
        $validationFunction,
        $isUpdate
    );

    if ($res === 0) {
        wp_send_json(array("success" => false, "data" => "An error occured while saving your shortcode settings."));
    } else {
        wp_send_json(array("success" => true, "data" => "Shortcode settings saved!"));
    }
}
add_action('wp_ajax_dynamic_shortcode_settings', 'ajaxds_ajax_dynamic_shortcode_settings');
add_action('wp_ajax_nopriv_dynamic_shortcode_settings', 'ajaxds_ajax_dynamic_shortcode_settings');



/** Validate if the sent shortcode settings are valid */
function ajaxds_validateShortcodeSettings(
    $shortcodeName,
    $ignoreAttributesParameters,
    $ignoreGetParameters,
    $ignorePostParameters,
    $placeholderName,
    $validationFunction
) {
    if (strlen(trim($shortcodeName)) === 0) {
        return "The shortcode name is invalid.";
    }

    $found = false;
    $shortcodes = ajaxds_getAllShortcodes();
    foreach ($shortcodes as $code => $function) {
        if ($code === $shortcodeName) {
            $found = true;
            break;
        }
    }

    if ($found === false) {
        return "This shortcode does not exist.";
    }

    $found = false;
    $placeholders = ajaxds_getAllPlaceholders();
    foreach ($placeholders as $code) {
        if ($code->placeholder_name === $placeholderName) {
            $found = true;
            break;
        }
    }

    if ($found === false) {
        return "This placeholder does not exist.";
    }

    if (strval($ignoreAttributesParameters) !== $ignoreAttributesParameters) {
        return 'The "Attributes Settings" value is invalid';
    }

    if (strval($ignoreGetParameters) !== $ignoreGetParameters) {
        return 'The "Get Parameters Settings" value is invalid';
    }

    if (strval($ignorePostParameters) !== $ignorePostParameters) {
        return 'The "Post Parameters Settings" value is invalid';
    }

    if (strval($validationFunction) !== $validationFunction) {
        return 'The "Validation Function Settings" value is invalid';
    }

    if (strlen($validationFunction) > 0 && !function_exists($validationFunction)) {
        return 'This validation function does not exist.';
    }

    return "";
}



/** Get the inner html of a domnode object */

function ajaxds_getInnerHTML($element)
{
    $innerHTML = "";
    $children  = $element->childNodes;

    foreach ($children as $child) {
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return trim($innerHTML);
}


function ajaxds_validatePlaceholderSettings($placeholderName, $data)
{
    if (strlen(trim($placeholderName)) === 0) {
        return "The placeholder name is invalid.";
    }

    if (strlen(trim($data)) === 0) {
        return "The placeholder value cannot be empty.";
    }

    return "";
}



/** AJAX load the settings of a given shortcode */
function ajaxds_ajax_dynamic_shortcode_load_shortcode_settings()
{
    ajaxds_validateGenuineRequest('ajaxds_ajaxNonceLoadSettingsShortcode_');


    $shortcodeName = ajaxds_sanitizeBasicTextInput($_POST['shortcode_name']);

    if (strlen(trim($shortcodeName)) === 0) {
        return "The shortcode name is invalid.";
    }

    // Load the shortcode settings
    $exist = ajaxds_getIfShortcodeExist($shortcodeName);

    if (!$exist) {
        $res = ajaxds_getDefaultParametersShortcode();
        wp_send_json(array("success" => true, "settings" => $res));
        // wp_send_json(array("success" => false, "data" => "Shortcode name is invalid, or you haven't set any settings for this shortcode in the admin panel."));
    } else {
        $res = ajaxds_LoadShortcodeSettings($shortcodeName);
        if ($res === 0) {
            wp_send_json(array("success" => false, "data" => "An error occured while loading your shortcode settings."));
        } else {
            wp_send_json(array("success" => true, "settings" => $res));
        }
    }
}
add_action('wp_ajax_dynamic_shortcode_load_shortcode_settings', 'ajaxds_ajax_dynamic_shortcode_load_shortcode_settings');
add_action('wp_ajax_nopriv_dynamic_shortcode_load_shortcode_settings', 'ajaxds_ajax_dynamic_shortcode_load_shortcode_settings');



/** AJAX load the settings of a given placeholder */
function ajaxds_ajax_dynamic_shortcode_load_placeholder_settings()
{
    ajaxds_validateGenuineRequest('ajaxds_ajaxNonceLoadSettingsPlaceholder_');


    $placeholderName = ajaxds_sanitizeBasicTextInput($_POST['placeholder_name']);

    if (strlen(trim($placeholderName)) === 0) {
        return "The placeholder name is invalid.";
    }

    // Load the placeholder settings
    $exist = ajaxds_getIfPlaceholderExist($placeholderName);

    if (!$exist) {
        wp_send_json(array("success" => false, "data" => "This placeholder does not exist."));
    } else {
        $res = ajaxds_LoadPlaceholderSettings($placeholderName);
        if ($res === 0) {
            wp_send_json(array("success" => false, "data" => "An error occured while loading your placeholder settings."));
        } else {
            wp_send_json(array("success" => true, "settings" => $res));
        }
    }
}
add_action('wp_ajax_dynamic_shortcode_load_placeholder_settings', 'ajaxds_ajax_dynamic_shortcode_load_placeholder_settings');
add_action('wp_ajax_nopriv_dynamic_shortcode_load_placeholder_settings', 'ajaxds_ajax_dynamic_shortcode_load_placeholder_settings');




/** AJAX that saves the selected placeholder settings */
function ajaxds_ajax_dynamic_shortcode_settings_placeholder()
{
    // Validate if the request is genuine
    ajaxds_validateGenuineRequest('ajaxds_ajaxNonceSaveSettingsPlaceholder_');


    $placeholderName = ajaxds_sanitizeBasicTextInput($_POST['placeholderName']) ?? "";

    $data = ajaxds_sanitizePlaceholder($_POST['data']);

    // Validate the settings
    $errorMsg = ajaxds_validatePlaceholderSettings(
        $placeholderName,
        $data
    );

    if (strlen($errorMsg) > 0) {
        wp_send_json(array("success" => false, "data" => $errorMsg));
    }

    // Insert or update the placeholder settings
    $isUpdate = ajaxds_getIfPlaceholderExist($placeholderName);

    $res = ajaxds_updateInsertPlaceholderSettings(
        $placeholderName,
        $data,
        $isUpdate
    );

    if ($res === 0) {
        wp_send_json(array("success" => false, "data" => "An error occured while saving your placeholder settings."));
    } else {
        if ($isUpdate) {
            wp_send_json(array("success" => true, "data" => "Placeholder settings saved!", "isUpdate" => true));
        } else {
            wp_send_json(array("success" => true, "data" => "Placeholder created!", "isUpdate" => false));
        }
    }
}
add_action('wp_ajax_dynamic_shortcode_settings_placeholder', 'ajaxds_ajax_dynamic_shortcode_settings_placeholder');
add_action('wp_ajax_nopriv_dynamic_shortcode_settings_placeholder', 'ajaxds_ajax_dynamic_shortcode_settings_placeholder');




/** AJAX that delete the selected placeholder */
function ajaxds_ajax_dynamic_shortcode_delete_placeholder()
{
    ajaxds_validateGenuineRequest('ajaxds_ajaxNonceDeletePlaceholder_');

    $placeholderName = ajaxds_sanitizeBasicTextInput($_POST['placeholderName']) ?? "";

    if (strlen(trim($placeholderName)) === 0) {
        wp_send_json(array("success" => false, "data" => "The placeholder name is invalid."));
    }

    // Insert or update the placeholder settings
    $exist = ajaxds_getIfPlaceholderExist($placeholderName);

    if (!$exist) {
        wp_send_json(array("success" => false, "data" => "This placeholder does not exist."));
    }

    $res = ajaxds_deletePlaceholder(
        $placeholderName
    );

    if ($res === 0) {
        wp_send_json(array("success" => false, "data" => "An error occured while deleting your placeholder settings."));
    } else {
        wp_send_json(array("success" => true, "data" => "Placeholder deleted!"));
    }
}
add_action('wp_ajax_dynamic_shortcode_delete_placeholder', 'ajaxds_ajax_dynamic_shortcode_delete_placeholder');
add_action('wp_ajax_nopriv_dynamic_shortcode_delete_placeholder', 'ajaxds_ajax_dynamic_shortcode_delete_placeholder');
