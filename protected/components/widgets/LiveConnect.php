<?php

class LiveConnect extends CWidget {

    public $state;
    public $liveLoginUrl = "site/login";

    public function init() {
        if(app()->user->isGuest()){
            // generate csrf token
            $this->state = md5(rand());
            app()->cache->set('live-state', $this->state);
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'info',
                'label' => 'Login with Windows',
                'icon' => 'icon-th-large',
                'loadingText' => '<i class="icon-spinner icon-spin"></i> Login with Windows',
                'htmlOptions' => array('id' => 'live-login'),
            ));
        }
    }

    public function run() {
        if(app()->user->isGuest()){
            cs()->registerScriptFile('//js.live.net/v5.0/wl.js', CClientScript::POS_HEAD);
            $this->liveLoginUrl = url($this->liveLoginUrl);
            $this->renderJavascript();
        }
    }

    private function renderJavascript() {
        $script = <<<EOD
        WL.init({ client_id: 'WINDOWS_LIVE_ID'
                , redirect_uri: "{$this->liveLoginUrl}"
                , scope: ["wl.signin", "wl.emails"]
            });

        function liveButton(response) {
            var l = document.getElementById("live-login");
            l.onclick = function(){
                WL.login().then(
                    function (response) {
                        console.log(response);
                    }, function (responseFailed) {
                        console.log(responseFailed);
                    }
                );
            }
        }

        WL.getLoginStatus(liveButton);
        WL.Event.subscribe('auth.statusChange', liveButton);
EOD;

        cs()->registerScript('live-connect', $script, CClientScript::POS_BEGIN);
    }

}