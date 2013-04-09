<?php

class FacebookController extends Controller {

    public $defaultAction = 'facebook';

    public function actionFacebook() {
        if (app()->request->isAjaxRequest) {
            $user = $_POST['user'];
            $session = $_POST['session'];
            if($session === app()->session->sessionID){
                // verify one last time that facebook knows this guy
                if($user['id']===app()->facebook->getUser()){
                    $model = User::model()->findByEmail($user['email']);
                    if(!empty($model)){
                        // facebook email matches one in the user database
                        $identity = new UserIdentity( $model->email , null );
                        $identity->_fbAuth = true;
                        $identity->authenticate();
                        if($identity->errorCode === UserIdentity::ERROR_NONE){
                            app()->user->login($identity, null);
                            echo json_encode(array('error' => false, 'success' => url('/')));
                            app()->end();
                        } else {
                            echo json_encode(array('error' => 'Authentication Failed'));
                            app()->end();
                        }
                    } else {
                        // nothing found, this person should register
                        echo json_encode(array('error' => 'You are not registered. Please register an account.'));
                        app()->end();
                    }
                } else {
                    echo json_encode(array('error' => 'Authentication Failed'));
                    app()->end();
                }
            } else {
                echo json_encode(array('error' => 'Session Conflict'));
                app()->end();
            }
        } else {
            throw new CHttpException(403);
        }
    }

}