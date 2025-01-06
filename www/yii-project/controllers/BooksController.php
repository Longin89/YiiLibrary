<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use app\models\Links;
use app\models\Books;
use app\models\Genres;
use app\models\Authors;


class BooksController extends Controller
{

    private $searchModel;

    //Инициализируем общую модель поиска

    public function init()
    {
        parent::init();
        $this->searchModel = new Books();
    }

    //Метод пагинации с указанием количества элементов на страницу и общим количеством элементов, по умолчанию сортируем книги по ID

    private function actionPagination($query, $sort = 'id')
    {
        $count = $query->count();

        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $count,
        ]);

        $books = $query
            ->orderBy([$sort => SORT_ASC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [$books, $pagination, $sort];
    }

    //Метод собирает всю информацию о всех книгах

    private function getBooksData($query)
    {
        $genres = Genres::find()->select(['genre_name', 'id'])->orderBy('genre_name')->indexBy('id')->column();
        $authors = Authors::find()->select(['concat(first_name, " ", last_name)', 'id'])->orderBy('id')->column();
        if($authors) {
            $authors = array_combine(range(1, count($authors)), $authors);

        }
        $sort = Yii::$app->request->getQueryParam('sort', 'id');

        list($books, $pagination, $_) = $this->actionPagination($query, $sort);

        return [
            'genres' => $genres,
            'books' => $books,
            'sort' => $sort,
            'authors' => $authors,
            'searchModel' => $this->searchModel,
            'pagination' => $pagination,
            'totalPages' => max(1, ceil($pagination->totalCount / $pagination->pageSize)),
        ];
    }

    //Метод отображает всю информацию о книгах, забирая ее из метода getBooksData()

    public function actionIndex()
    {
        $query = Books::find();
        $data = $this->getBooksData($query);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    //Метод фильтрует книги по году, жанру и автору, а так-же сортирует по названию, году и ID(по умолчанию)

    public function actionFilter()
    {
        $query = Books::find();
        $requestParams = Yii::$app->request->queryParams;

        if ($this->searchModel->load($requestParams)) {

            //Делаем выборку согласно указанным критериям

            if ($this->searchModel->year_filter) {
                $query->andWhere(['year' => $this->searchModel->year_filter]);
            }
            if ($this->searchModel->genre_filter) {
                $query->andWhere(['genre_id' => $this->searchModel->genre_filter]);
            }
            if ($this->searchModel->author_filter) {
                $query->joinWith('links')
                    ->andWhere(['links.author_id' => $this->searchModel->author_filter]);
            }
        }

        //Сортируем согласно указанным критериям и возвращаем результат

        $sort = Yii::$app->request->getQueryParam('sort', 'id');

        $sortingFields = ['title', 'year', 'id'];
        if (in_array($sort, $sortingFields)) {
            $query->orderBy([$sort => SORT_ASC]);
        } else {
            $query->orderBy(['id' => SORT_ASC]);
        }

        $data = $this->getBooksData($query);

        return $this->render('index', [
            'data' => $data
        ]);
    }

    //Метод для создания новой книги

    public function actionCreate()
    {
        $book = new Books();
        $genres = Genres::find()->select(['genre_name', 'id'])->indexBy('id')->column();
        $authors = Authors::find()->all();

        //Валидируем запрос и начинаем транзакцию, если автор не выбран - выводим ошибку (кастомную, не присутствует в валидации)

        if ($book->load(Yii::$app->request->post()) && $book->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if (empty(Yii::$app->request->post('author_id'))) {
                    throw new \Exception('Пожалуйста, выберите хотя бы одного автора.');
                }

                //Если книга сохраняется, данные валидны

                if ($book->save()) {

                    // Сохраняем связи с авторами

                    foreach (Yii::$app->request->post('author_id') as $authorId) {
                        $link = new Links();
                        $link->book_id = $book->id;
                        $link->author_id = $authorId;
                        if (!$link->save(false)) {
                            throw new \Exception('Ошибка при сохранении связи с автором');
                        }
                    }
                    //Коммитим транзакцию и уведомляем об успехе

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Книга успешно добавлена!');

                    // Если что-то пошло не так - уведомляем об ошибке

                    return $this->refresh();
                } else {
                    throw new \Exception('Ошибка валидации данных');
                }
            } catch (\Exception $e) {

                //Откатываем изменения

                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        //Возвращаемся на страницу создания книги со списками

        return $this->render('create', [
            'book' => $book,
            'genres' => $genres,
            'authors' => $authors
        ]);
    }

    //Метод для обновления существующей книги

    public function actionUpdate($id)
    {
        //Находим книгу по ID, жанры и авторов

        $book = Books::findOne($id);
        $genres = Genres::find()->select(['genre_name', 'id'])->indexBy('id')->column();
        $authors = Authors::find()->all();

        //Если указан несуществующий ID - уведомляем об ошибке

        if ($book === null) {
            throw new \Exception('Книга не найдена.');
        }

        // Получаем текущие связи с авторами

        $currentAuthorIds = array_column($book->links, 'author_id');

        //Пробуем начать транзакцию после post-запроса

        if ($book->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                //Если не выбран ни один автор - уведомляем об ошибке

                if (empty(Yii::$app->request->post('author_id'))) {
                    throw new \Exception('Пожалуйста, выберите хотя бы одного автора.');
                }

                //Если книга сохраняется

                if ($book->save()) {

                    // Обновляем связи с авторами

                    $newAuthorIds = Yii::$app->request->post('author_id', []);

                    // Удаляем устаревшие связи, если что-то пошло не так - уведомляем об ошибке

                    foreach ($currentAuthorIds as $authorId) {
                        if (!in_array($authorId, $newAuthorIds)) {
                            $link = Links::findOne(['book_id' => $book->id, 'author_id' => $authorId]);
                            if ($link !== null && !$link->delete()) {
                                throw new \Exception('Ошибка при удалении связи с автором');
                            }
                        }
                    }

                    // Добавляем новые связи, если что-то пошло не так - уведомляем об ошибке

                    foreach ($newAuthorIds as $authorId) {
                        if (!in_array($authorId, $currentAuthorIds)) {
                            $link = new Links();
                            $link->book_id = $book->id;
                            $link->author_id = $authorId;
                            if (!$link->save(false)) {
                                throw new \Exception('Ошибка при сохранении связи с автором');
                            }
                        }
                    }

                    // Коммитим транзакцию

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Книга успешно обновлена!');

                    return $this->redirect(['index']);
                } else {
                    throw new \Exception('Ошибка валидации данных');
                }

                //Откатываем изменения

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        // Получаем список ID авторов, связанных с книгой

        $selectedAuthorIds = array_column($book->links, 'author_id');

        // Возвращаемся на страницу, вместе со всей информацией

        return $this->render('update', [
            'book' => $book,
            'selectedAuthorIds' => $selectedAuthorIds,
            'genres' => $genres,
            'authors' => $authors
        ]);
    }

    // Метод удаления книги

    public function actionDelete($id)
    {
        // Начинаем транзакцию

        $transaction = Yii::$app->db->beginTransaction();

        try {

            // Ищем книгу по ID, если не находим - выводим ошибку

            $book = Books::findOne($id);

            if ($book === null) {
                throw new \Exception('Книга не найдена.');
            }

            // Удаляем связи с авторами, если что-то пошло не так - выводим ошибку

            foreach ($book->links as $link) {
                if (!$link->delete()) {
                    throw new \Exception('Ошибка при удалении связи с автором');
                }
            }

            // Удаляем саму книгу, если что-то пошло не так-выводим ошибку

            if (!$book->delete()) {
                throw new \Exception('Ошибка при удалении книги');
            }

            // Коммитим транзакцию и выводим сообщение

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Книга успешно удалена!');

            // Возвращаемся на страницу с книгами

            return $this->redirect(['index']);
        } catch (\Exception $e) {

            // Откатываем транзакцию и выводим ошибку

            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        // Возвращаемся на страницу с книгами

        return $this->render('index', [
            'book' => $book,
        ]);
    }
}
