<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Genres;


class GenresController extends Controller
{
    // Метод отображения страницы авторов(сортировка по ID)

    public function actionIndex()
    {
        $genres = Genres::find()
            ->orderBy('id')
            ->all();

        return $this->render('index', [
            'genres' => $genres
        ]);
    }

    // Метод создания нового жанра

    public function actionCreate()
    {
        $newGenre = new Genres();
        $genres = Genres::find()->orderBy('id')->all();

        // Проверяем post-запрос

        if ($this->request->isPost) {
            $genre_name = trim($this->request->post('genre_name'));

            if (!empty($genre_name)) {

                // Если жанр уже существует - выводим сообщение об ошибке

                $existingGenre = Genres::findOne(['genre_name' => $genre_name]);

                if ($existingGenre && !$existingGenre->isNewRecord) {
                    Yii::$app->session->setFlash('error', 'Жанр с таким именем уже существует');
                } else {
                    $newGenre->genre_name = $genre_name;

                    if ($newGenre->validate()) {

                        // Если post-запрос прошел валидацию - начинаем транзакцию

                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            if ($newGenre->save()) {

                                // Коммитим транзакцию и выводим сообщение о добавлении записи

                                $transaction->commit();
                                Yii::$app->session->setFlash('success', 'Жанр успешно добавлен!');

                                return $this->redirect(['index']);
                            } else {

                                // Если что-то пошло не так - выводим сообщение об ошибке

                                throw new \Exception('Ошибка валидации данных');
                            }
                        } catch (\Exception $e) {

                            // Откатываем транзакцию и выводим сообщение об ошибке

                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', $e->getMessage());
                        }
                    }
                }
            } else {

                // Если поле не заполнено - выводим соответствующее сообщение

                Yii::$app->session->setFlash('error', 'Пожалуйста, заполните поле "Наименование жанра"');
            }
        }

        // Возвращаем страницу с жанрами

        return $this->render('index', [
            'genres' => $genres
        ]);
    }

    // Метод обновления жанра

    public function actionUpdate($id)
    {
        // Ищем жанр по ID, если не найден - выводим ошибку

        $genres = Genres::findOne($id);

        if ($genres === null) {
            throw new \Exception('Жанр не найден.');
        }

        // Если найден - формируем post-запрос и сохраняем

        if ($genres->load(Yii::$app->request->post()) && $genres->save()) {
            Yii::$app->session->setFlash('success', 'Жанр успешно обновлен!');

            return $this->redirect(['index']);
        } else {

            return $this->render('update', [
                'genres' => $genres
            ]);
        }
    }
}
