<?php

class UserController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl',
            'postOnly + delete',
        );
    }

    public function accessRules() {
        return array(
            // Actions: index, create, update, password, newPassword, forgotPassword, delete
            array('allow',
                'actions' => array('captcha', 'create', 'forgotPassword'),
                'expression' => 'app()->user->isGuest()',
            ),
            array('allow',
                'actions' => array('password', 'newPassword', 'update'),
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('index', 'create', 'register', 'delete'),
                'expression' => 'app()->user->isAdmin()',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'foreColor' => 0x333333,
            ),
        );
    }

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $model = new User('search');
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
            $criteria->mergeWith($model->search());
        }
        $dataProvider = new CActiveDataProvider('User', array(
                    'pagination' => array('pageSize' => 10),
                    'criteria' => $criteria,
                ));
        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCreate() {
        if(app()->user->isAdmin()){
            $model = new User('create');
            $email = 'adminCreate';
            $redirect = array('/user/index');
        } else {
            $model = new User('register');
            $email = 'userCreate';
            $redirect = array('/site/index');
        }
        if(isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if($model->validate()) {
                $model = User::create($_POST['User']);
                $model->activate = User::encrypt(microtime() . $model->password);

                Shared::debug($model->pass1);
                $mail = new Mailer($email, array('username' => $model->email, 'password' => $model->pass1, 'activate' => $model->activate));
                /**
                 * Be sure to configure properly! Check https://github.com/Synchro/PHPMailer for documentation.
                 */
                $mail->render();
                $mail->From = app()->params['adminEmail'];
                $mail->FromName = app()->params['adminEmailName'];
                $mail->Subject = 'Your '.app()->name.' Account';
                $mail->AddAddress($model->email);
                if($mail->Send()) {
                    $model->save();
                    $mail->ClearAddresses();
                    app()->user->setFlash('success', 'Account created and details sent to users inbox.');
                    $this->redirect($redirect);
                } else {
                    app()->user->setFlash('error', 'Error while sending email: ' . $mail->ErrorInfo);
                }
            }
        }
        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id) {
        $user = app()->user->getUser();
        if (isset($user->id) && $user->id === $id || app()->user->isAdmin()) {
            // only accessable by id holder or admin
            $model = $this->loadModel($id);
            $this->performAjaxValidation($model);
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->validate()) {
                    $model->save();
                    app()->user->setFlash('success', 'Saved');
                }
            }
            $model->password = '';
            $this->render('update', array('model' => $model));
        } else {
            // access denied for this user
            throw new CHttpException(403, 'Access Denied.');
        }
    }

    public function actionDelete($id) {
        //if($this->authAdminOnly()){
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        //}
    }

    public function actionPassword($id) {
        $user = app()->user->getUser();
        if (isset($user->id) && $user->id === $id || app()->user->isAdmin()) {
            $model = $this->loadModel($id);
            $model->setScenario('changePassword');
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->validate()) {
                    $model->password = crypt($_POST['User']['pass1'], Randomness::blowfishSalt());
                    if ($model->save()) {
                        app()->user->setFlash('success', 'Saved new password!');
                        $this->redirect(array('update', 'id' => $model->id));
                    }
                }
            }

            $this->render('/user/password', array('model' => $model));
        } else {
            // access denied for this user
            throw new CHttpException(403, 'Access Denied.');
        }
    }

    public function actionForgotPassword() {
        $model = new User('passwordReset');
        $model->setScenario('forgotPassword');
        $hash = '';
        //$this->performAjaxValidation($model);
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                // find the correct user
                $model = User::model()->findByEmail($_POST['User']['email']);
                // generate the md5 hash
                $timestamp = time();
                $hash = crypt($model->email . $model->password . $timestamp, Randomness::blowfishSalt());
                Shared::debug($hash);
                //$hash = md5($model->email . $model->password . $timestamp);
                $model->password_reset = $timestamp;
                // save the timestamp (password reset is good for 24 hours only)
                $model->save();

                $mail = new Mailer('forgotPass', array('hash' => $hash));
                /**
                 * Be sure to configure properly! Check https://github.com/Synchro/PHPMailer for documentation.
                 */
                $mail->render();
                $mail->From = app()->params['adminEmail'];
                $mail->FromName = 'Yii Skeleton App Mailer';
                $mail->Subject = 'Yii Skeleton App Password Reset';
                $mail->AddAddress($model->email);
                if ($mail->Send()) {
                    $mail->ClearAddresses();
                    app()->user->setFlash('success', 'Please check your email for further instructions.');
                    $this->redirect(array('/site/index'));
                } else {
                    app()->user->setFlash('error', 'Error while sending email: ' . $mail->ErrorInfo);
                }
            }
        }
        $this->render('forgot_password', array('model' => $model));
    }

    public function actionNewPassword($req) {
        // lookup users, who requested a password change
        $since = strtotime(Shared::toDatabase(time()) . " -1 day");
        $users = User::model()->findAllBySql("SELECT * FROM user WHERE password_reset > $since");
        $found = null;
        foreach ($users as $model) {
            if ($req === crypt($model->email . $model->password . $model->password_reset, $req)) {
                $found = $model;
                break;
            }
        }
        if ($found != null) {
            $model->setScenario('resetPass');
            // reset the password
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->validate()) {
                    $found->setPassword($_POST['User']['pass1']);
                    if ($found->save()) {
                        app()->user->setFlash('success', 'Your password has been reset.');
                        $this->redirect(app()->user->getHomeUrl());
                    }
                }
            }
            $this->render('new_password', array('model' => $found));
            app()->end();
        }
        // display not found screen
        throw new CHttpException(400, '<p>This password reset link is not valid. To reset your password, please <a href="' . url('user/forgotpassword') . '">initiate a new request</a>.</p>');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
