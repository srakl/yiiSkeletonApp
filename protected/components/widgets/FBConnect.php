<?php

class FBConnect extends CWidget {

    public $channel;
    public $userSession;
    public $fbAppId = "471779039535216";
    public $fbLoginButtonId = "fblogin";
    public $facebookLoginUrl = "facebook";
    public $facebookPermissions = "email,user_likes";

    public function init() {
        if(app()->user->isGuest()){
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'primary',
                'label' => 'Login with Facebook',
                'icon' => 'icon-facebook-sign',
                'loadingText' => '<i class="icon-spinner icon-spin"></i> Login with Facebook',
                'htmlOptions' => array('id' => $this->fbLoginButtonId),
            ));
        }
    }

    public function run() {
        if(app()->user->isGuest()){
            $this->channel = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.components.widgets.FBConnect'), false, 1);
            $this->facebookLoginUrl = url($this->facebookLoginUrl);
            $this->userSession = app()->session->sessionID;
            $this->renderJavascript();
        }
    }

    private function renderJavascript() {
        $script = <<<EOL
        window.fbAsyncInit = function() {
            FB.init({ appId: '{$this->fbAppId}'
                , channel: '{$this->channel}/channel.html'
                , status: true
                , cookie: true
                , xfbml: true
                , oauth: true
            });

            function updateButton(response) {
                var b = document.getElementById("{$this->fbLoginButtonId}");
                b.onclick = function(){
                    $("#{$this->fbLoginButtonId}").button("loading");
                    FB.login(function(response) {
                        if(response.authResponse) {
                            FB.api('/me', function(user) {
                                $.ajax({ type : 'post'
                                    , url: '{$this->facebookLoginUrl}'
                                    , data: ({ user: user
                                        , session: "{$this->userSession}"
                                    })
                                    , dataType: 'json'
                                    , success: function(data){
                                        if(data.error == 0){
                                            window.location.href = data.success;
                                        } else {
                                            showError(data.error);
                                            $("#{$this->fbLoginButtonId}").button("reset");
                                        }
                                    }
                                });
                            });	   
                        } else { $("#{$this->fbLoginButtonId}").button("reset"); }
                    }, {scope: '{$this->facebookPermissions}'});
                }
            }
                        
            FB.getLoginStatus(updateButton);
            FB.Event.subscribe('auth.statusChange', updateButton);	

        };
        
        
        (function(d){var e,id = "fb-root";if( d.getElementById(id) == null ){e = d.createElement("div");e.id=id;d.body.appendChild(e);}}(document));
        (function(d){var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];if (d.getElementById(id)) {return;} js = d.createElement('script'); js.id = id; js.async = true; js.src = "//connect.facebook.net/en_US/all.js"; ref.parentNode.insertBefore(js, ref); }(document));
EOL;

        cs()->registerScript('facebook-connect', $script, CClientScript::POS_BEGIN);
    }

}