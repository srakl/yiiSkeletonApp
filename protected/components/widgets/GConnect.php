<?php

class GConnect extends CWidget {

    public $gAppId;
    public $gLoginButtonId = "glogin";
    public $gLoginUrl = "google";

    public function init() {
        if(app()->user->isGuest()){
            $this->gLoginUrl = url($this->gLoginUrl);
            $this->gAppId = "505023187211.apps.googleusercontent.com";
            $this->renderJavascript();
        }
    }

    public function run() {
        if(app()->user->isGuest()){
            cs()->registerScriptFile('https://plus.google.com/js/client:plusone.js', CClientScript::POS_HEAD);
            echo '<div id="customBtn" class="btn btn-danger">
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
                'clientid': '{$this->gAppId}',
                'cookiepolicy': 'single_host_origin',
                'requestvisibleactions': 'http://schemas.google.com/AddActivity',
                'scope': 'https://www.googleapis.com/auth/userinfo.email'
            });
        })();
        var googleCallback = function(authResult) {
            if(authResult['g-oauth-window']){
                if(authResult['code']) {
                    alert("(Still in development - Travis Stroud) " + authResult['code']);
                } else {
                    alert("action canceled");
                }
            }
        };
EOL;

        cs()->registerScript('google-connect', $script, CClientScript::POS_END);
    }

}