<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Books */

$this->title = 'Добавить книгу';
?>
<h1 class="fw-semibold">Добавить книгу</h1>

<!-- Форма для добавления новой книги -->

<?php $form = ActiveForm::begin(); ?>
<?= Html::hiddenInput('_csrf', Yii::$app->request->getCsrfToken(), ['bypass-url-check' => true]) ?>

<?= $form->field($book, 'title')->textInput()->label('Название книги'); ?>
<?= $form->field($book, 'year')->textInput()->label('Год'); ?>

<?= $form->field($book, 'genre_id')->label('Жанр')->dropDownList($genres, ['prompt' => 'Выберите жанр']) ?>

<div class="form-group field-books-author_ids required mb-5">
    <label for="author_id" class="mt-2">Авторы</label>
    <div class="d-flex flex-wrap row-gap-2">

        <!-- Перебираем авторов из массива -->
        <?php if ($authors): ?>
            <?php foreach ($authors as $author): ?>
                <div class="checkbox w-25">
                    <label>
                        <input type="checkbox" name="author_id[]" value="<?= $author->id ?>" />
                        <?= $author->first_name . ' ' . $author->last_name ?>
                    </label>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>В базе нет авторов</p>
        <?php endif; ?>
    </div>
</div>

<div class="form-group text-center">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success mx-2']) ?>
    <span class="mb-3"><?= Html::a('Назад', ['index'], ['class' => 'btn btn-danger mx-2']) ?></span>
</div>

<?php ActiveForm::end(); ?>