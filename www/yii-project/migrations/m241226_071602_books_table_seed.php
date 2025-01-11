<?php

use yii\db\Migration;
use Faker\Factory;

class m241226_071602_books_table_seed extends Migration
{
    public function safeUp()
    {
        $faker = Factory::create();
        $booksQty = 25;

        // Добавляем жанры

        $genres = [];
        for ($i = 0; $i < 3; $i++) {
            $genreName = $faker->word;
            $this->insert('{{%genres}}', ['genre_name' => $genreName]);
            $genres[] = $this->db->getLastInsertId();
        }

        // Добавляем книги с существующими жанрами

        for ($i = 0; $i < $booksQty; $i++) {
            $this->insert('{{%books}}', [
                'title' => $faker->sentence,
                'genre_id' => $faker->randomElement($genres),
                'year' => $faker->numberBetween(1980, 2000),
            ]);
        }

        // Добавляем авторов

        for ($i = 0; $i < 10; $i++) {
            $this->insert('{{%authors}}', [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
            ]);
        }

        // Добавляем связь между книгами и авторами

        for ($j = 1; $j <= $booksQty; $j++) {

            $this->insert('{{%links}}', [
                'book_id' => $j,
                'author_id' => rand(1, 9)
            ]);
        }
    }
}
