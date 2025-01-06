<?php

namespace app\models;

use yii\db\ActiveRecord;

class Authors extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%authors}}';
    }


    //Правила валидации для полей

    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required', 'message' => 'Пожалуйста, заполните поле.'],
            [['first_name', 'last_name'], 'string', 'max' => 155],
            ['first_name', 'trim'],
            ['last_name', 'trim']
        ];
    }


    //Атрибуты полей

    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
        ];
    }
}
