<?php

class UserController extends Controller {
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    /* public function filters()
      {
      return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
      );
      }
     * 
     */

    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'foreColor'=>0x333333,
            ),
        );
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        if (app()->user->isAdmin()) {
            $id = app()->user->getUser()->id;
            $model = User::model()->findByPk($id);
            $dataProvider = new CActiveDataProvider('User');
            $this->render('index', array(
                'dataProvider' => $dataProvider,
                'model' => $model,
            ));
        } else {
            if (app()->user->isGuest()) {
                app()->user->loginRequired();
            } else {
                // take this user to their user update page
                $this->redirect(array('/user/update', 'id' => app()->user->getUser()->id));
            }
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed

      public function actionView($id) {
      $this->render('view', array(
      'model' => $this->loadModel($id),
      ));
      }
     */

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionRegister() {
        if(!app()->user->isAdmin()){
            $model = new User('register');
            //$this->performAjaxValidation($model);
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->validate()) {
                    $model = User::create($_POST['User']);
                    $model->activate = User::encrypt(microtime() . $model->password);

                    // send the user an activation link email (later)
                    $model->save();
                    app()->user->setFlash('success', 'Registration successful. Check your email.');
                    $this->redirect(app()->user->getHomeUrl());
                }
            }
            // render the create form
            $this->render('create', array('model' => $model));
        } else {
            $this->redirect(array('/user/create'));
        }
    }
    
    public function actionCreate() {
        if(app()->user->isAdmin()){
            $model = new User('create');
            
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->validate()) {
                    $model = User::create($_POST['User']);
                    $model->activate = User::encrypt(microtime() . $model->password);
                    
                    Shared::debug($model);
                    // generate a password for this user
                    //$model->save();
                }
            }
            $this->render('create', array('model' => $model));
        } else {
            $this->redirect(array('/user/register'));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
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

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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

    /**
     * Request a password reset.
     */
    public function actionForgotPassword() {
        if(app()->user->isGuest()){
            $model = new User('passwordReset');
            $model->setScenario('forgotPassword');
            $hash = ''; // temporary until emails are set up
            //$this->performAjaxValidation($model);
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->validate()) {
                    // find the correct user
                    $model = User::model()->findByEmail($_POST['User']['email']);
                    // generate the md5 hash
                    $timestamp = time();
                    $hash = md5($model->email . $model->password . $timestamp);
                    $model->password_reset = $timestamp;
                    // save the hash to the users table row
                    $model->save();
                    // email the user their reset link
                    /*
                      $mail = new Email('resetPassword');
                      $mail->addPlaceholders(array(
                      'email' => $model->email_address,
                      'full_name' => $model->getFullName(),
                      'reset_link' => absUrl('/user/newPassword', array('req' => $hash))));
                      $mail->addRecipient($model->email_address, $model->getFullName());
                      $mail->send();
                     */
                }
            }
            $this->render('forgot_password', array(
                'model' => $model,
                'hash' => $hash, // temporary until emails are set up
            ));
        } else {
            // you are logged in... how did you forget your password?
            $this->redirect(array('/user/password', 'id' => app()->user->id));
        }
    }

    /**
     * 
     */
    public function actionNewPassword($req) {
        // lookup users, who requested a password change
        $since = strtotime(Shared::toDatabase(time()) . " -1 day");
        $users = User::model()->findAllBySql("SELECT * FROM user WHERE password_reset > $since");
        $found = null;
        foreach ($users as $model) {
            if ($req == md5($model->email . $model->password . $model->password_reset)) {
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

                    /* log the user in
                      $userArray = array();
                      $userArray['username'] = $found['email_address'];
                      $userArray['password'] = $_POST['User']['pass1'];
                      $userArray['rememberMe'] = 0;
                      $model = new LoginForm;
                      $model->attributes = $userArray;
                      if ($model->validate() && $model->login()) {
                      $this->redirect(app()->user->getHomeUrl());
                      app()->end();
                      } */
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
