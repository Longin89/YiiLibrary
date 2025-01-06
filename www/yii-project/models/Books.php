<?php

namespace app\models;

use yii\db\ActiveRecord;

class Books extends ActiveRecord

{
    //Переменные для фильтрации по году, жанру и автору

    public $year_filter;
    public $genre_filter;
    public $author_filter;

    public static function tableName()
    {
        return '{{%books}}';
    }

    //Методы определения отношений между моделью Books и другими моделями

    public function getLinks()
    {
        return $this->hasMany(Links::className(), ['book_id' => 'id']);
    }

    public function getAuthors()
    {
        return $this->hasMany(Authors::className(), ['id' => 'author_id'])
            ->via('links');
    }

    public function getGenres()
    {
        return $this->hasOne(Genres::className(), ['id' => 'genre_id']);
    }

    //Метод форматирования имен авторов в строку

    public function formatAuthors()
    {
        $authors = [];
        foreach ($this->authors as $author) {
            $authors[] = $author->first_name . ' ' . $author->last_name;
        }
        return implode(', ', $authors);
    }


    //Правила валидации для полей

    public function rules()
    {
        return [
            [['title', 'year', 'genre_id'], 'required', 'message' => 'Пожалуйста, заполните поле.'],
            [['title'], 'string', 'max' => 155],
            [['year', 'genre_id'], 'integer', 'message' => 'Пожалуйста, только числовые значения.'],
            [['year_filter'], 'integer', 'message' => 'Пожалуйста, только числовые значения.'],
            [['genre_filter'], 'string', 'message' => 'Пожалуйста, только строковые значения.'],
            [['author_filter'], 'integer'],
            ['title', 'trim'],
            ['year', 'trim']
        ];
    }

    //Атрибуты полей

    public function attributeLabels()
    {
        return [
            'title' => 'Название книги',
            'year' => 'Год',
            'genre_id' => 'Жанр',
            'year_filter' => 'Фильтр по году',
            'genre_filter' => 'Фильтр по жанру',
            'author_filter' => 'Фильтр по автору',
        ];
    }
}
