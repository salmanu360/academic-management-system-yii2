<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visitors".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $cnic
 * @property string $company
 * @property integer $to_meet
 * @property string $representing
 * @property string $address
 * @property string $date
 * @property integer $branch_id
 */
class Visitors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $user_type;
    public $to_meet_stu;
    public static function tableName()
    {
        return 'visitors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'cnic', 'representing', 'address', 'date', 'branch_id'], 'required'],
            [['to_meet', 'branch_id'], 'integer'],
            [['representing', 'address'], 'string'],
            [['date'], 'safe'],
            [['name', 'company'], 'string', 'max' => 555],
            [['email'], 'string', 'max' => 255],
            [['phone', 'cnic'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'cnic' => 'Cnic',
            'company' => 'Company',
            'to_meet' => 'To Meet',
            'representing' => 'Representing',
            'address' => 'Address',
            'date' => 'Date',
            'branch_id' => 'Branch ID',
        ];
    }
}
