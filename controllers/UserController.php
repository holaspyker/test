<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Users;
use yii\rest\ActiveController;

class UserController extends ActiveController {

    public $modelClass = 'app\models\Users';

    /*
      Action overwrite the actions create,update and delete
     * 
     */

    public function actions() {
        return [
            'index' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    /**
     * action create ,create a new user with balance 0 and calculate the balance
     * first_name: string  required
     * last_name : string , required
     * gender   :string ,optional
     * email :string ,unique,required
     * balance :double set to 0
     * bonus double ,set to 0,
     * bonus_percentage double ,ramdom 
     * 
     * @return user object 
     * 
     * 
     * POST    ipAdress/Test/index.php/user
     */
    public function actionCreate() {

        $user = new Users();
        $user->attributes = \yii::$app->request->post();
        if ($user->validate()) {
            //the user is correct , the syystema calcaulate el bonus and set the balance to 0
            $user->balance = 0;
            $user->bonus_percentage = rand(5, 20) / 100;
            $user->save();

            return   $user;
        } else {
            return  $user->errors;
        }
    }

    /**
      update an user
     * first_name: string  required
     * last_name : string , required
     * gender   no allowed to modify
     * email :string ,unique,required
     * balance :no allowed to modify
     * bonus no allowed to modify,
     * bonus_no allowed to modify
     * return the user object
     * 
     * PUT ipadress/test/index.php/user/:id
     * 
     *      */
    public function actionUpdate() {


        try {
            //look up the user
            $user = Users::find()->where(['id' => $_GET['id']])->one();
            if (count($user) > 0)  {
                //parse the vars
                parse_str(file_get_contents("php://input"), $post_vars);
                if (isset($post_vars['first_name']))
                    $user->first_name = $post_vars['first_name'];
                if (isset($post_vars['last_name']))
                    $user->last_name = $post_vars['last_name'];
                if (isset($post_vars['email']))
                    $user->email = $post_vars['email'];
                if (isset($post_vars['country']))
                    $user->country = $post_vars['country'];
                if ($user->validate()) {
                    $user->save();
                    return $user;
                } else
                    return  $user->errors;
            } return 'The user not found';
        } catch (\Exception $e) {
            return 'The user not found';
        }
    }

    function actionDelete() {
        return 'This action is not created';
    }

}
