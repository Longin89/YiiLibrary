<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var string $first_name */
/** @var string $last_name */

$this->title = 'Добавить автора';
?>
<div class="site-index">

  <div class="jumbotron bg-transparent">
    <h1 class="fw-semibold m-0">Добавить автора</h1>
  </div>

  <!-- Форма для добавления автора -->

  <form action="<?= Url::to(['create']) ?>" method="post">
    <?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken(), ['bypass-url-check' => true]) ?>

    <label for="first_name">
      Имя
    </label>
    <?= Html::textInput('first_name', '', ['class' => 'form-control mb-3', 'placeholder' => 'Имя']) ?>

    <label for="last_name">
      Фамилия
    </label>
    <?= Html::textInput('last_name', '', ['class' => 'form-control mb-3', 'placeholder' => 'Фамилия']) ?>

    <div class="form-group text-center">
      <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success mx-2']) ?>
    </div>
  </form>

  <h1 class="fw-semibold m-0">Список авторов</h1>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">Имя</th>
        <th scope="col">Фамилия</th>
        <th scope="col" class="text-center">Действие</th>
      </tr>
    </thead>
    <tbody>

      <!-- Перебираем авторов в массиве -->

      <?php if (count($authors) > 0): ?>
        <?php foreach ($authors as $author): ?>
          <tr class="table-secondary">
            <th scope="row"><?php echo $author->id; ?></th>
            <td><?php echo $author->first_name; ?></td>
            <td><?php echo $author->last_name; ?></td>
            <td class="text-center">
              <?= Html::a('Обновить', ['update', 'id' => $author->id], ['class' => 'btn btn-sm btn-primary mx-2']); ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td>В базе нет авторов</td>
        </tr>
      <?php endif; ?>

    </tbody>
  </table>
  <div class="form-group text-center">
    <span class="mb-3"><?= Html::a('Назад', ['/site/index'], ['class' => 'btn btn-danger mx-2']) ?></span>
  </div>