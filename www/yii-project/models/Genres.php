<?php

namespace app\models;

use yii\db\ActiveRecord;


class Genres extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%genres}}';
    }

    //Правила валидации для полей

    public function rules()
    {
        return [
            [['genre_name'], 'required', 'message' => 'Пожалуйста, заполните поле.'],
            [['genre_name'], 'string', 'max' => 255],
            [['genre_name'], 'unique', 'message' => 'Жанр уже существует.'],
            ['genre_name', 'trim']
        ];
    }
    
    //Атрибуты полей

    public function attributeLabels()
    {
        return [
            'genre_name' => 'Название жанра',
        ];
    }
}
