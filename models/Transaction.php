<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trans".
 *
 * @property int $id
 * @property int $id_user
 * @property int $amount
 * @property string $date_insert
 * @property string $transcol
 *
 * @property Users $users
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trans';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'amount'], 'required'],
            [['id_user', 'amount'], 'number'],
            [['date_insert'], 'safe'],
           
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Id User',
            'amount' => 'Amount',
            'date_insert' => 'Date Insert',
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'id']);
    }
}
