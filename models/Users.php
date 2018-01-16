<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $email
 * @property string $country
 * @property double $balance
 * @property double $bonus
 * @property double $bonus_percentage
 *
 * @property Trans $id0
 */
class Users extends \yii\db\ActiveRecord
{
    
 const SCENARIO_CREATE = 'create';
    
     public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::className(),
                'only' => ['index', 'view','create'],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
                
            ],
           
        ];
        
    }
    
    
    
    public function scenarios()
 {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['first_name','last_name','email','gender','country']; 
        return $scenarios; 
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'email', 'country'], 'required'],
            ['email', 'email'],
            [['balance', 'bonus', 'bonus_percentage'], 'number'],
            [['first_name', 'last_name', 'gender',  'country'], 'string', 'max' => 45],
            [['email'], 'unique'],
           // [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Trans::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'gender' => 'Gender',
            'email' => 'Email',
            'country' => 'Country',
            'balance' => 'Balance',
            'bonus' => 'Bonus',
            'bonus_percentage' => 'Bonus Percentage',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(Trans::className(), ['id' => 'id']);
    }
}
