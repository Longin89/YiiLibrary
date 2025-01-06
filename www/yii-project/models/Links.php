<?php

namespace app\models;

use yii\db\ActiveRecord;

class Links extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%links}}';
    }

    //Методы определения отношений между моделью Links и другими моделями

    public function getBook()
    {
        return $this->hasOne(Books::className(), ['id' => 'book_id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(Authors::className(), ['id' => 'author_id']);
    }

    //Правила валидации для полей

    public function rules()
    {
        return [
            [['book_id', 'author_id'], 'required']
        ];
    }
}
