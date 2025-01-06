<?php

use yii\helpers\Html;
use yii\helpers\Url;


/** @var yii\web\View $this */

$this->title = 'My Books Database';
?>
<div class="site-index">

  <div class="jumbotron bg-transparent">
    <h1 class="fw-semibold m-0">Добавить жанр</h1>
  </div>

  <div class="body-content">

    <!-- Форма для добавления жанра -->

    <form action="<?= Url::to(['create']) ?>" method="post">
      <?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken(), ['bypass-url-check' => true]) ?>

      <label for="genre_name">
        Наименование жанра
      </label>
      <?php
      echo Html::textInput('genre_name', '', ['class' => 'form-control col-sm-10', 'placeholder' => 'Наименование жанра']);
      ?>
      <div class="form-group text-center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success mx-2 mt-1']) ?>
      </div>
    </form>
    <div class="jumbotron bg-transparent">
      <h1 class="fw-semibold m-0">Список жанров</h1>
    </div>
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Наименование жанра</th>
          <th scope="col" class="text-center">Действие</th>
        </tr>
      </thead>
      <tbody>

        <!-- Перебираем жанры из массива -->

        <?php if (count($genres) > 0): ?>
          <?php foreach ($genres as $genre): ?>
            <tr class="table-secondary">
              <th scope="row"><?php echo $genre->id; ?></th>
              <td><?php echo $genre->genre_name; ?></td>
              <td class="text-center">
                <span> <?= Html::a('Обновить', ['update', 'id' => $genre->id], ['class' => 'btn btn-sm btn-primary mx-2']); ?> </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td>В базе нет жанров</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <div class="form-group text-center">
      <span class="mb-3"><?= Html::a('Назад', ['/site/index'], ['class' => 'btn btn-danger mx-2']) ?></span>
    </div>
  </div>
</div>