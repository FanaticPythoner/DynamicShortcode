<?php


function ajaxds_getNavbar()
{
    $imgUrl = AJAXDS_PATH . 'resources/imgs/DynamicShortcode_Logo_Thumbnail.jpg';
    return <<<EOD
    <nav class="navbar navbar-dark navbar-expand-md bg-dark navigation-clean">
        <div class="container"><a class="navbar-brand" href="/wp-admin/admin.php?page=dynamic-shortcode-shortcodes">
        <img src="$imgUrl" width="50"></a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item"></li>
                    <li class="nav-item"></li>
                    <li class="nav-item"><a class="nav-link" href="/wp-admin/admin.php?page=dynamic-shortcode-shortcodes">Shortcodes</a></li>
                    <li class="nav-item"><a class="nav-link" href="/wp-admin/admin.php?page=dynamic-shortcode-placeholders">Placeholders</a></li>
                    <li class="nav-item"><a class="nav-link" href="/wp-admin/admin.php?page=dynamic-shortcode-globalsettings">Global Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/wp-admin/admin.php?page=dynamic-shortcode-help">Help</a></li>
                    <li class="nav-item"><a class="nav-link" style="color: rgba(0, 255, 52, 0.5);font-weight: bold;font-size: 20px;padding-top: 4px;" href="/wp-admin/admin.php?page=dynamic-shortcode-donate">Keep It Alive</a></li>
                    <li class="nav-item"><a class="nav-link" href="/wp-admin/admin.php?page=dynamic-shortcode-notice">Notice</a></li>
                    <li class="nav-item"></li>
                </ul>
            </div>
        </div>
    </nav>
EOD;
}


function ajaxds_getJsVariableHtml()
{
    $js = <<<EOD
    function ajaxds_globalSearchVariable(value, mainShortcodeHtml) {
        for ( var i in window ) {
            if (window[i] === value && i !== "ajaxds_shortcodeDivId_" + mainShortcodeHtml){
                return i;
            }
        }

        return null;
    }

EOD;
    return $js;
}

function ajaxds_getJsGetShortcodeAjaxHtml()
{
    $debug = ajaxds_getDebugJs();

    $js = <<<EOD
    function ajaxds_dynamicShortcodeAjax(url, data, ajaxds_shortcodeDivId, mainShortcodeHtml, isJsStr, strPlaceHolder) {
        var isPlaceholderValid = strPlaceHolder.length > 0;
        var isJsVariable = isJsStr === "1";
        if (isPlaceholderValid && !isJsVariable){
            var loadDom = strToDom(strPlaceHolder);
            insertBefore(loadDom, document.getElementById(ajaxds_shortcodeDivId));
        }
        console.log(data);

        jQuery.get({
            
            url: url,
            type: 'post',
            data: data,
            retryLimit : 2,
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            success: function(data) {
                if (isJsVariable){
                    var varNameByValue = ajaxds_globalSearchVariable(ajaxds_shortcodeDivId, mainShortcodeHtml);
                    eval(varNameByValue + " = '" + data.data + "';");
                }
                else{
                    var recVal = data.data;
                    if (recVal === undefined || recVal === null){
                        recVal = '';
                    }
                    else{
                        recVal = recVal.toString();
                    }
                    
                    var placDiv = document.getElementById(ajaxds_shortcodeDivId);
                    jQuery(recVal).appendTo(document.getElementById(ajaxds_shortcodeDivId));
                }

                $debug
                if (isPlaceholderValid && !isJsVariable){
                    loadDom.remove();
                }
            },

            error: function(xhr, textStatus, errorThrown) {

                    this.retryLimit--;
                    if (this.retryLimit > 0) {
                        jQuery.ajax(this);
                        return;
                    }
                    else{
                        var errMsg = "An error occured on our side while recovering data. Shortcode ID: " + ajaxds_shortcodeDivId;
                        if (isJsVariable){
                            var varNameByValue = ajaxds_globalSearchVariable(ajaxds_shortcodeDivId, mainShortcodeHtml);
                            eval(varNameByValue + " = '';");
                            alert(errMsg);
                        }
                        else{
                            document.getElementById(ajaxds_shortcodeDivId).innerHTML = errMsg;
                        }
                        
                        console.error({"Dynamic Shortcode Ajax - ": errorThrown});
                        if (isPlaceholderValid && !isJsVariable){
                            loadDom.remove();
                        }
                    }
                
            }

        });
    }

EOD;
    return $js;
}



function ajaxds_getJsSaveSettingsShortcode()
{
    $debug = ajaxds_getDebugJs();
    $js = <<<EOD
    function  ajaxds_getFormData(form){
        var unindexed_array = form.serializeArray();
        var indexed_array = {};
    
        jQuery.map(unindexed_array, function(n, i){
            indexed_array[n['name']] = n['value'];
        });
    
        return indexed_array;
    }


    function ajaxds_showReset(){
        var succAlert = document.getElementById("alertSuccess");
        var dangAlert = document.getElementById("alertDanger");
        succAlert.innerHTML = "";                    
        succAlert.style.display = "none";
        dangAlert.innerHTML = "";
        dangAlert.style.display = "none";
    }

    function ajaxds_showDanger(message){
        var succAlert = document.getElementById("alertSuccess");
        var dangAlert = document.getElementById("alertDanger");
        succAlert.innerHTML = "";                    
        succAlert.style.display = "none";
        dangAlert.innerHTML = message;
        dangAlert.style.display = "block";
        jQuery(window).scrollTop(0);
    }


    function ajaxds_showSuccess(message){
        var succAlert = document.getElementById("alertSuccess");
        var dangAlert = document.getElementById("alertDanger");
        dangAlert.innerHTML = "";
        dangAlert.style.display = "none";
        succAlert.innerHTML = message;
        succAlert.style.display = "block";
        jQuery(window).scrollTop(0);
    }


    function ajaxds_SaveSettingsShortcode() {
        ajaxds_showReset();
        var valPname = document.getElementById('placeholderName').getAttribute("value");
        if (valPname === null){
            ajaxds_showDanger("You must choose a placeholder");
            return;
        }
        
        var valSname = document.getElementById('shortcodeName').getAttribute("value");
        if (valSname === null){
            ajaxds_showDanger("You must choose a shortcode");
            return;
        }
        
        ajaxds_toggleLoader();
        var form = jQuery("#form_data");
        var dataForm = ajaxds_getFormData(form);

        var data = {
            userId: ajaxds_mainUserId,
            ipAddress: ajaxds_mainIpAddress,
            nonce: ajaxds_mainNonce,
            action: "dynamic_shortcode_settings"
        };

        data =  Object.assign({}, data, dataForm);

        jQuery.get({          
            url: '/wp-admin/admin-ajax.php',
            type: 'post',
            data: data,
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            success: function(data) {
                if (data.success){
                    ajaxds_showSuccess(data.data);
                }
                else{
                    ajaxds_showDanger(data.data);
                }
                ajaxds_toggleLoader();
                $debug
            },

            error: function(err) {
                ajaxds_showDanger(err);
                ajaxds_toggleLoader();
                console.error("Dynamic Shortcode Ajax:");
                console.error(err);
            }

        });
    }

EOD;
    return $js;
}



function ajaxds_getJsLoadSettingsShortcode()
{
    $debug = ajaxds_getDebugJs();
    $debug = str_replace('data', 'data.settings', $debug);
    $js = <<<EOD
    function ajaxds_toggleLoader(){
        var container = document.getElementById('loaderContainer');
        if (container.style.display === "none"){
            container.style.display = "block";
        }
        else{
            container.style.display = "none";
        }
    }

    function  ajaxds_ajaxLoadSettingsShortcode(valSname){
        ajaxds_toggleLoader();
        ajaxds_showReset();

        var data = {
            userId: ajaxds_mainUserId,
            ipAddress: ajaxds_mainIpAddress,
            nonce: ajaxds_loadNonce,
            action: "dynamic_shortcode_load_shortcode_settings",
            shortcode_name: valSname
        };

        jQuery.get({          
            url: '/wp-admin/admin-ajax.php',
            type: 'post',
            data: data,
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            success: function(data) {
                if (data.success){
                    $debug
                    
                    var settings = data.settings;
                    if (settings["placeholder_name"] === ""){
                        document.getElementById("placeholderName").parentElement.querySelector('a').click();
                    }
                    else{
                        var aLinks = document.getElementById("placeholderName").parentElement.querySelectorAll('a');
                        for (var i = 0; i < aLinks.length; i++){
                            var currLink = aLinks[i];
                            if (currLink.innerHTML === settings["placeholder_name"]){
                                currLink.click();
                                break;
                            }
                        }
                    }

                    var jsCheck = document.getElementById("isJavascriptVariable");
                    var jsCheckLabel = document.querySelector('label[for="isJavascriptVariable"]');
                    if (jQuery(jsCheck).is(":checked")){
                        if (settings["is_javascript_variable"] === "0"){
                            jsCheckLabel.click();
                        }
                    }
                    else if (settings["is_javascript_variable"] === "1"){
                        jsCheckLabel.click();
                    }

                    document.getElementById("ignoreGetParameters").value = settings["get_parameters_ignore"];
                    document.getElementById("ignorePostParameters").value = settings["post_parameters_ignore"];
                    document.getElementById("validationFunction").value = settings["validation_function_name"];
                    document.getElementById("ignoreAttributesParameters").value = settings["attributes_ignore"];

                    var dynReplaceCheck = document.getElementById("enableDynamicReplace");
                    var dynReplaceCheckLabel = document.querySelector('label[for="enableDynamicReplace"]');
                    if (jQuery(dynReplaceCheck).is(":checked")){
                        if (settings["use_dynamic_replace"] === "0"){
                            dynReplaceCheckLabel.click();
                        }
                    }
                    else if (settings["use_dynamic_replace"] === "1"){
                        dynReplaceCheckLabel.click();
                    }
                }
                else{
                    ajaxds_showDanger(data.data);
                }

                // Reset focus
                if (document.activeElement != document.body) document.activeElement.blur();
                ajaxds_toggleLoader();
            },

            error: function(err) {
                ajaxds_showDanger(err);
                ajaxds_toggleLoader();
            }

        });
    }

EOD;
    return $js;
}



function ajaxds_getJsLoadSettingsPlaceholder()
{
    $debug = ajaxds_getDebugJs();
    $debug = str_replace('data', 'data.settings', $debug);
    $js = <<<EOD
    function ajaxds_toggleLoader(){
        var container = document.getElementById('loaderContainer');
        if (container.style.display === "none"){
            container.style.display = "block";
        }
        else{
            container.style.display = "none";
        }
    }

    function  ajaxds_ajaxLoadSettingsPlaceholder(valSname){
        ajaxds_toggleLoader();
        ajaxds_showReset();

        // var valSname = document.getElementById('placeholderName').value;
        // if (valSname === null){
        //     ajaxds_showDanger("You must choose a placeholder");
        //     return;
        // }

        var data = {
            userId: ajaxds_mainUserId,
            ipAddress: ajaxds_mainIpAddress,
            nonce: ajaxds_loadNonce,
            action: "dynamic_shortcode_load_placeholder_settings",
            placeholder_name: valSname
        };

        jQuery.get({          
            url: '/wp-admin/admin-ajax.php',
            type: 'post',
            data: data,
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            success: function(data) {
                if (data.success){
                    $debug
                    document.getElementById("data").value = data.settings["data"];
                }
                else{
                    ajaxds_showDanger(data.data);
                }

                // Reset focus
                if (document.activeElement != document.body) document.activeElement.blur();
                ajaxds_toggleLoader();
                var deletePlaceholder = document.getElementById('deletePlaceholder');
                deletePlaceholder.style.display = "inline";
            },

            error: function(err) {
                ajaxds_showDanger(err);
                ajaxds_toggleLoader();
            }

        });
    }

EOD;
    return $js;
}


function ajaxds_getJsDeletePlaceholder()
{
    $debug = ajaxds_getDebugJs();
    $js = <<<EOD
    function ajaxds_DeletePlaceholder(){
        var pEl = document.getElementById('placeholderName');
        var valSname = pEl.value;
        if (valSname === null || valSname === ''){
            ajaxds_showDanger("You must choose a placeholder before trying to delete it.");
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
                ajaxds_toggleLoader();
                var data = {
                    userId: ajaxds_mainUserId,
                    ipAddress: ajaxds_mainIpAddress,
                    nonce: ajaxds_deleteNonce,
                    action: "dynamic_shortcode_delete_placeholder",
                    placeholderName: valSname
                };
        
                jQuery.post({          
                    url: '/wp-admin/admin-ajax.php',
                    type: 'post',
                    data: data,
                    xhrFields: {
                        withCredentials: true
                    },
                    crossDomain: true,
                    success: function(data) {
                        if (data.success){
                            ajaxds_showSuccess(data.data);
                            pEl.value = '';
                            document.getElementById('editPladeholderId').querySelector('button').innerHTML = 'Pick Your Placeholder';
                            document.getElementById('data').value = '';
                            var allA = document.querySelectorAll('a[class="dropdown-item"]');
                            for (var i = 0; i < allA.length; i++){
                                if (allA[i].innerHTML === valSname){
                                    allA[i].remove();
                                    break;
                                }
                            }
                            document.getElementById('deletePlaceholder').style.display = 'none';

                            Swal.fire(
                                'Deleted!',
                                'Your placeholder "' + valSname + '" has been deleted.',
                                'success'
                            );
                        }
                        else{
                            ajaxds_showDanger(data.data);
                        }
                        ajaxds_toggleLoader();
                        $debug
                    },
        
                    error: function(err) {
                        ajaxds_showDanger(err);
                        ajaxds_toggleLoader();
                        console.error("Dynamic Shortcode Ajax:");
                        console.error(err);
                    }
        
                });                
            }
          })
    }

EOD;

    return $js;
}


function ajaxds_getJsSaveSettingsPlaceholder()
{
    $debug = ajaxds_getDebugJs();
    $js = <<<EOD
    function  ajaxds_getFormData(form){
        var unindexed_array = form.serializeArray();
        var indexed_array = {};
    
        jQuery.map(unindexed_array, function(n, i){
            indexed_array[n['name']] = n['value'];
        });
    
        return indexed_array;
    }


    function ajaxds_showReset(){
        var succAlert = document.getElementById("alertSuccess");
        var dangAlert = document.getElementById("alertDanger");
        succAlert.innerHTML = "";                    
        succAlert.style.display = "none";
        dangAlert.innerHTML = "";
        dangAlert.style.display = "none";
    }

    function ajaxds_showDanger(message){
        var succAlert = document.getElementById("alertSuccess");
        var dangAlert = document.getElementById("alertDanger");
        succAlert.innerHTML = "";                    
        succAlert.style.display = "none";
        dangAlert.innerHTML = message;
        dangAlert.style.display = "block";
        jQuery(window).scrollTop(0);
    }


    function ajaxds_showSuccess(message){
        var succAlert = document.getElementById("alertSuccess");
        var dangAlert = document.getElementById("alertDanger");
        dangAlert.innerHTML = "";
        dangAlert.style.display = "none";
        succAlert.innerHTML = message;
        succAlert.style.display = "block";
        jQuery(window).scrollTop(0);
    }


    function ajaxds_SaveSettingsPlaceholder() {
        var baseDropItemALink = `<a class="dropdown-item" href="Javascript:void(0);" style="color: rgb(255,255,255);" onclick="document.getElementById('placeholderName').setAttribute('value', this.innerHTML);
        this.parentElement.parentElement.querySelector('button').innerHTML = this.innerHTML;ajaxds_ajaxLoadSettingsPlaceholder(this.innerHTML);">[_LINK_NAME_]</a>`;

        ajaxds_showReset();
        
        var valSname = document.getElementById('placeholderName').value;
        if (valSname === null || valSname === ""){
            ajaxds_showDanger("You must choose a placeholder");
            return;
        }
        
        ajaxds_toggleLoader();
        var form = jQuery("#form_data");
        var dataForm = ajaxds_getFormData(form);

        var data = {
            userId: ajaxds_mainUserId,
            ipAddress: ajaxds_mainIpAddress,
            nonce: ajaxds_mainNonce,
            action: "dynamic_shortcode_settings_placeholder"
        };

        data =  Object.assign({}, data, dataForm);

        jQuery.post({          
            url: '/wp-admin/admin-ajax.php',
            type: 'post',
            data: data,
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            success: function(data) {
                if (data.success){
                    if (!data.isUpdate){
                        var menu = document.getElementById('editPladeholderId').querySelector('div[class="dropdown-menu"]');
                        var newEl = baseDropItemALink.replace('[_LINK_NAME_]', valSname);
                        menu.innerHTML = menu.innerHTML + newEl;
                    }
                    ajaxds_showSuccess(data.data);
                }
                else{
                    ajaxds_showDanger(data.data);
                }
                ajaxds_toggleLoader();
                $debug
            },

            error: function(err) {
                ajaxds_showDanger(err);
                ajaxds_toggleLoader();
                console.error("Dynamic Shortcode Ajax:");
                console.error(err);
            }

        });
    }

EOD;
    return $js;
}



function ajaxds_getJsToggleAll()
{
    $js = <<<EOD
    document.addEventListener('DOMContentLoaded', function(){
        jQuery('#toggleAll').change(function() {
            var allChks = document.querySelectorAll('label[for^="chkToggle_"]');
            var scrollPos = document.documentElement.scrollTop;
            var scrollPosHor = document.documentElement.scrollLeft;
            for (var i = allChks.length - 1; i >= 0; i--){
                var currItem = allChks[i];
                if (this.checked){
                    if (!jQuery('#' + currItem.getAttribute('for')).is(":checked")){
                        currItem.click();
                    }
                }
                else{
                    if (jQuery('#' + currItem.getAttribute('for')).is(":checked")){
                        currItem.click();
                    }
                }
            }
            window.scrollTo(scrollPosHor, scrollPos);
            if (document.activeElement != document.body) document.activeElement.blur();
        });
    });
EOD;
    return $js;
}


function ajaxds_getJsInitCheck()
{
    $js = <<<EOD
    function ajaxds_checkIfAllChecked(lst){
        for (var i = lst.length - 1; i >= 0; i--){
            if (!jQuery(lst[i]).is(":checked")){
                return false;
            }
        }

        return true;
    }

    document.addEventListener('DOMContentLoaded', function(){
        var allChks = document.querySelectorAll('input[id^="chkToggle_"][will-check="true"]');
        var allChksNoFilter = document.querySelectorAll('input[id^="chkToggle_"]');
        var scrollPos = document.documentElement.scrollTop;
        var scrollPosHor = document.documentElement.scrollLeft;

        if (allChksNoFilter.length === allChks.length && !ajaxds_checkIfAllChecked(allChksNoFilter)){
            document.getElementById('toggleAll').click();
        }
        else{
            for (var i = allChks.length - 1; i >= 0; i--){
                var currItem = allChks[i];
                if (!jQuery(currItem).is(":checked")){
                    currItem.click();
                }
            }
        }

        window.scrollTo(scrollPosHor, scrollPos);
        if (document.activeElement != document.body) document.activeElement.blur();
    });
EOD;
    return $js;
}




function ajaxds_getJsSaveGlobalSettings()
{
    $debug = ajaxds_getDebugJs();
    $js = <<<EOD
    function ajaxds_toggleLoader(){
        var container = document.getElementById('loaderContainer');
        if (container.style.display === "none"){
            container.style.display = "block";
        }
        else{
            container.style.display = "none";
        }
    }

    function  ajaxds_getFormData(form){
        var unindexed_array = form.serializeArray();
        var indexed_array = {};
    
        jQuery.map(unindexed_array, function(n, i){
            indexed_array[n['name']] = n['value'];
        });
    
        var checkbox = document.querySelectorAll('input[type="checkbox"][id^="chkToggle_"]');
        for (var i = 0; i < checkbox.length; i++){
            var currItem = checkbox[i];
            if (!jQuery(currItem).is(':checked')){
                indexed_array[currItem.getAttribute('name')] = "off";
            }
        }

        return indexed_array;
    }


    function ajaxds_showReset(){
        var succAlert = document.getElementById("alertSuccess");
        var dangAlert = document.getElementById("alertDanger");
        succAlert.innerHTML = "";                    
        succAlert.style.display = "none";
        dangAlert.innerHTML = "";
        dangAlert.style.display = "none";
    }

    function ajaxds_showDanger(message){
        var succAlert = document.getElementById("alertSuccess");
        var dangAlert = document.getElementById("alertDanger");
        succAlert.innerHTML = "";                    
        succAlert.style.display = "none";
        dangAlert.innerHTML = message;
        dangAlert.style.display = "block";
        jQuery(window).scrollTop(0);
    }


    function ajaxds_showSuccess(message){
        var succAlert = document.getElementById("alertSuccess");
        var dangAlert = document.getElementById("alertDanger");
        dangAlert.innerHTML = "";
        dangAlert.style.display = "none";
        succAlert.innerHTML = message;
        succAlert.style.display = "block";
        jQuery(window).scrollTop(0);
    }


    function ajaxds_SaveSettingsGlobal() {
        ajaxds_showReset();
        ajaxds_toggleLoader();
        var form = jQuery("#form_data");
        var dataForm = ajaxds_getFormData(form);

        var data = {
            userId: ajaxds_mainUserId,
            ipAddress: ajaxds_mainIpAddress,
            nonce: ajaxds_mainNonce,
            action: "dynamic_shortcode_save_globalsettings"
        };

        data =  Object.assign({}, data, dataForm);

        jQuery.get({          
            url: '/wp-admin/admin-ajax.php',
            type: 'post',
            data: data,
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true,
            success: function(data) {
                if (data.success){
                    ajaxds_showSuccess(data.data);
                }
                else{
                    ajaxds_showDanger(data.data);
                }
                ajaxds_toggleLoader();
                $debug
            },

            error: function(err) {
                ajaxds_showDanger(err);
                ajaxds_toggleLoader();
                console.error("Dynamic Shortcode Ajax:");
                console.error(err);
            }

        });
    }

EOD;
    return $js;
}
