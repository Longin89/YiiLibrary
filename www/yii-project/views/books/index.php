<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;


/** @var yii\web\View $this */
/** @var app\models\Books[] $books */
/** @var yii\data\Pagination $pagination */
/** @var int $totalPages */

$this->title = 'Список книг';

// Скрипт подтверждения удаления книги

$confirm = <<<JS
$(document).ready(function() {
    $('.delete-book').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (confirm("Удалить эту книгу?")) {
            window.location.href = url;
        }
    });
});
JS;

// Регистрируем JS в представлении

$this->registerJs($confirm);

?>

<div class="site-index">

  <h1 class="fw-semibold">Список книг</h1>

  <!-- Форма для фильтрации и сортировки -->

  <?php $form = ActiveForm::begin([
    'action' => ['filter'],
    'method' => 'get',
    'options' => ['id' => 'filter-form']
  ]); ?>

  <?= $form->field($data['searchModel'], 'year_filter')->textInput(['id' => 'year-filter-input']) ?>

  <?= $form->field($data['searchModel'], 'genre_filter')->dropDownList(
    $data['genres'],
    [
      'prompt' => 'Все жанры',
      'id' => 'genre-filter-select'
    ]
  ) ?>

  <?= $form->field($data['searchModel'], 'author_filter')->dropDownList(
    $data['authors'],
    [
      'prompt' => 'Все авторы',
      'id' => 'author-filter-select'
    ]
  ) ?>

  <div class="form-group">
    <?= Html::submitButton('Фильтровать', ['class' => 'btn btn-primary']) ?>
    <?= Html::button('Сбросить', [
      'class' => 'btn btn-outline-secondary reset-filter',
      'type' => 'button'
    ]) ?>
  </div>

  <div class="form-group">
    <?= Html::label('Сортировка:', null, ['for' => 'sort-select']) ?>
    <?= Html::dropDownList('sort', $data['sort'], [
      'id' => 'По ID',
      'year' => 'По году',
      'title' => 'По названию',
    ], ['id' => 'sort-select']) ?>
  </div>

  <?php ActiveForm::end(); ?>

  <div class="body-content">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Название</th>
          <th scope="col">Год</th>
          <th scope="col">Жанр</th>
          <th scope="col">Автор</th>
          <th scope="col" class="text-center">Действие</th>
        </tr>
      </thead>
      <tbody>

        <!-- Перебираем книги из массива -->

        <?php if (count($data['books']) > 0): ?>
          <?php foreach ($data['books'] as $book): ?>
            <tr class="table-secondary">
              <th scope="row">
                <?= $book->id; ?>
              </th>
              <td><?= $book->title; ?></td>
              <td><?= $book->year; ?></td>
              <td><?= $book->genres->genre_name; ?></td>

              <!-- Вынимаем имена и фамилии солдат из массива -->

              <?php $authors = $book->formatAuthors(); ?>
              <td><?= $authors ?></td>
              <td class="text-center">
                <span> <?= Html::a('Обновить', ['update', 'id' => $book->id], ['class' => 'btn btn-sm btn-primary mx-2']); ?> </span>
                <span> <?= Html::a('Удалить', ['delete', 'id' => $book->id], ['class' => 'btn btn-sm btn-danger delete-book mx-2']) ?> </span>
              </td>
            </tr>
          <?php endforeach; ?>

          <!-- Если книг в базе нет - выводим соответствующее сообщение -->

        <?php else: ?>
          <tr>
            <td>В базе нет книг</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <div class="container d-flex justify-content-center my-3">
      <span class="mb-3"><?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success mx-1']) ?></span>
      <span class="mb-3"><?= Html::a('На главную', ['/site/index'], ['class' => 'btn btn-danger mx-1']) ?></span>
    </div>
  </div>

  <!-- Вывод пагинации и общего количества книг в базе -->

  <?php
  echo "Страница " . $data['pagination']->getPage() + 1 . " из " . $data['totalPages'];
  echo "<br><hr>";
  echo LinkPager::widget(['pagination' => $data['pagination']]);
  echo 'Всего книг в базе: ' . $data['pagination']->totalCount;
  ?>
</div>

<!-- Скрипт для сброса фильтров и сортировки -->

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var resetButton = document.querySelector('.reset-filter');

    resetButton.addEventListener('click', function(e) {
      e.preventDefault();

      const yearInputField = document.getElementById('year-filter-input');
      const genreSelect = document.getElementById('genre-filter-select');
      const authorSelect = document.getElementById('author-filter-select');
      const sortSelect = document.getElementById('sort-select');

      if (yearInputField && genreSelect && authorSelect && sortSelect) {
        yearInputField.value = '';
        genreSelect.selectedIndex = 0;
        authorSelect.selectedIndex = 0;
        sortSelect.selectedIndex = 0;

        const form = document.getElementById('filter-form');
        if (form) {
          form.submit();
        }
      } else {
        console.error("Элемент не найден");
      }
    });
  });
</script>