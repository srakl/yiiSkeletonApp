<?php

class GoogleController extends Controller {

    public $defaultAction = 'google';

    public function actionGoogle() {
        if (app()->request->isAjaxRequest) {
            $code = $_POST['code'];
            $client = app()->google->client;
            // here we will use the google php sdk to find out who this person is

            echo json_encode(array('error' => false, 'success' => 'The server says your code was: '.$code));
            app()->end();
        } else {
            throw new CHttpException(403);
        }
    }

}