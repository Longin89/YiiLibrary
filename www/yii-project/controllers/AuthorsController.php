<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Authors;

class AuthorsController extends Controller
{
    public function actionIndex()
    {
        //Получаем список авторов, отсортированных по ID

        $authors = Authors::find()->orderBy('id')->all();
        return $this->render('index', [
            'authors' => $authors,
        ]);
    }

    public function actionCreate()
    {
        //Создаем экземпляр автора

        $newAuthor = new Authors();
        $authors = Authors::find()->orderBy('id')->all();

        //Присваиваем данные из post-запроса переменным

        if ($this->request->isPost) {
            $firstName = trim($this->request->post('first_name'));
            $lastName = trim($this->request->post('last_name'));

            if (!empty($firstName) && !empty($lastName)) {
                $newAuthor->first_name = $firstName;
                $newAuthor->last_name = $lastName;

                //Если данные валидны - начинаем транзакцию, если на каком то этапе происходит ошибка - откатываемся и уведомляем о ней

                if ($newAuthor->validate()) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($newAuthor->save()) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Автор успешно добавлен!');

                            return $this->redirect(['index']);
                        } else {
                            throw new \Exception('Ошибка валидации данных');
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', $e->getMessage());
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', 'Пожалуйста, заполните поля "Имя" и "Фамилия"');
            }
        }

        //Возвращаем авторов

        return $this->render('index', [
            'authors' => $authors
        ]);
    }

    public function actionUpdate($id)
    {
        //Находим автора по ID, если нет - уведомляем об ошибке

        $author = Authors::findOne($id);

        if ($author === null) {
            throw new \Exception('Автор не найден.');
        }

        //Если автор загружен и сохранен в базу - сообщаем и перенаправляем на страницу списка, иначе вернем автора для редактирования

        if ($author->load(Yii::$app->request->post()) && $author->save()) {
            Yii::$app->session->setFlash('success', 'Автор успешно обновлен!');

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'author' => $author
            ]);
        }
    }
}
