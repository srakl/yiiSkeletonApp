<?php

class GConnect extends CWidget {

    public $gClientId;
    public $gLoginButtonId = "glogin";
    public $gLoginUrl = "google";

    public function init() {
        if(app()->user->isGuest()){
            $this->gLoginUrl = url($this->gLoginUrl);
            $this->gClientId = app()->params['google']['clientId'];
            $this->renderJavascript();
        }
    }

    public function run() {
        if(app()->user->isGuest()){
            cs()->registerScriptFile('https://plus.google.com/js/client:plusone.js', CClientScript::POS_HEAD);
            echo '<div id="customBtn" class="btn btn-danger" data-loading-text="&lt;i class="icon-spinner icon-spin"&rt;&lt;/i&rt; Login with Google">
                <i class="icon-google-plus"></i>
                <span class="buttonText">Login with Google</span>
            </div>';
        }
    }

    private function renderJavascript() {
        $script = <<<EOL
        (function render() {
            gapi.signin.render('customBtn', {
                'callback': 'googleCallback',
                'clientid': '{$this->gClientId}',
                'cookiepolicy': 'single_host_origin',
                'requestvisibleactions': 'http://schemas.google.com/AddActivity',
                'scope': 'https://www.googleapis.com/auth/userinfo.email'
            });
        })();
        var googleCallback = function(authResult) {
            if(authResult['g-oauth-window']){
                if(authResult['code']) {
                    $.ajax({ type : 'post'
                        , url: '{$this->gLoginUrl}'
                        , data: ({ code: authResult['code'] })
                        , dataType: 'json'
                        , success: function(data){
                            if(data.error == 0){
                                alert(data.success);
                            } else {
                                showError(data.error);
                            }
                        }
                        //, processData: false
                    });
                }
            }
        };
EOL;

        cs()->registerScript('google-connect', $script, CClientScript::POS_END);
    }

}