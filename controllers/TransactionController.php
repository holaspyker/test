<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Users;
use app\models\Transaction;
use yii\rest\ActiveController;

class TransactionController extends ActiveController {

    public $modelClass = 'app\models\Transaction';

    /**
     * 
     * actions return the methods not overwritten 
     */
    public function actions() {
        return [
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

    /*
     * Create a transaction 
     * id_user : int required user who creates the transaction
     * amount   :double, amount of transaction(positive deposit, negative  withdraw)
     * date_insert timestamp , creationof the transaction
     * refused , if the transaction has been refused(not enough balance)
     *  Every 3rd deposit of the customer should be awarded with bonus on the deposit amount according to his bonus parameter

     * POST server_name/index.php/transaction
     * 
     *    */

    public function actionCreate() {

        try {
            $transaccion = new Transaction();
            $transaccion->attributes = \yii::$app->request->post();
            $user = Users::findOne(['id' => $_POST['id_user']]);
            if ($transaccion->validate()) {
                 //Check the balance is enugh
                if ($user->balance + $transaccion->amount >= 0) {
                    $user->balance += $transaccion->amount;
                    $customer = Yii::$app->db->createCommand('select count(id) as number from `trans` where amount>0 and id_user=:id ')
                                    ->bindParam(':id', $_POST['id_user'])->queryOne();

                    //add the bonus in the bonus field
                    if ((int) $customer['number'] % 3 == 0 && $transaccion->amount > 0) {
                        $user->bonus = $transaccion->amount * $user->bonus_percentage;
                    }
                       $reponse = $transaccion;
                   //the balance is not enough , we save the transaction but set the rfused field to 1
                } else {
                        $transaccion->refused = 1;
                        $reponse = array('message'=> 'not enough balanace' ,'data'=>$transaccion);
                }
               //the transaction is not validate
            } else {
                $reponse = $transaccion->errors;
            }
            
             $transaction = Yii::$app->db->beginTransaction();
                try {
                    $transaccion->save();
                     $user->save();
                    $transaction->commit();
                    
                    
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return  $e->getMessage();
                }
            
            
             return $reponse;
   
        } catch (\Exception $e) {
            return 'The user does not exist';
        }
    }

    /*

     * 
     * 
     * action index 
     * 
     * params 
     * days (int) , number of days of the report ,  no given ,a week by default 
     * return a list of transaction by  days and country
     * 
     * GET  server_name/index.php/transaction?days=:number
     *      */

    function actionIndex() {


        if (isset($_GET['days']))
            $days = $_GET['days'];
        else
            $days = 7;
        $report = Yii::$app->db->createCommand('select date(date_insert) as date , country , count(distinct(id_user)) as users , COUNT(CASE WHEN amount > 0 THEN 1 END) AS deposit  , sum(CASE WHEN amount > 0 THEN amount  else 0 END )as total_deposit , 
                                COUNT(CASE WHEN amount < 0 THEN 1  END) as withdraw  , sum(CASE WHEN amount < 0 THEN amount else 0  END )as total_withdraw 
                             from trans inner join users  on users.id=`trans`.`id_user`  where (date_insert > curdate() - interval :days day ) and refused is null group by date(date_insert),country')
                        ->bindParam(':days', $days)->queryAll();

        return  $report;
    }

}
