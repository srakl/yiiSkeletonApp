<?php

class FacebookController extends Controller {

    public $defaultAction = 'facebook';

    public function actionFacebook() {
        if (app()->request->isAjaxRequest) {
            $user = $_POST['user'];
            // verify one last time that facebook knows this guy
            if($user['id'] === app()->facebook->getUser()){
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
                        echo json_encode(array('error' => 'System Authentication Failed', 'code' => 'auth'));
                        app()->end();
                    }
                } else {
                    // nothing found, this person should register
                    $model = new User('create');
                    $model->email = $user['email'];
                    $model->first_name = ($user['first_name']?$user['first_name']:'');
                    $model->last_name = ($user['last_name']?$user['last_name']:'');
                    $model = User::create($model->attributes);
                    $model->activate = User::encrypt(microtime() . $model->password);
                    $model->save();
                    $mail = new Mailer('fbRegister', array('username' => $model->email, 'password' => $model->pass1, 'activate' => $model->activate));
                    /**
                     * Be sure to configure properly! Check https://github.com/Synchro/PHPMailer for documentation.
                     */
                    $mail->render();
                    $mail->From = app()->params['adminEmail'];
                    $mail->FromName = app()->params['adminEmailName'];
                    $mail->Subject = 'Your ' . app()->name . ' Account';
                    $mail->AddAddress($model->email);
                    Shared::debug($mail);
                    if ($mail->Send()) {
                        $mail->ClearAddresses();
                    }
                    $identity = new UserIdentity( $model->email , null );
                    $identity->_fbAuth = true;
                    $identity->authenticate();
                    if($identity->errorCode === UserIdentity::ERROR_NONE){
                        app()->user->login($identity, null);
                        app()->user->setFlash('info', '<strong>Registration Success</strong> Facebook profile successfully registered. Check your email for activation instructions.');
                        echo json_encode(array('error' => false, 'success' => url('/')));
                        app()->end();
                    } else {
                        echo json_encode(array('error' => 'System Authentication Failed', 'code' => 'auth'));
                        app()->end();
                    }
                }
            } else {
                // fb user id past from ajax does not match who facebook says they are...
                echo json_encode(array('error' => 'Facebook Authentication Failed', 'code' => 'fb_auth'));
                app()->end();
            }
        } else {
            throw new CHttpException(403);
        }
    }

}