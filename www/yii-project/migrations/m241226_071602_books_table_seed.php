<?php

use yii\db\Migration;
use Faker\Factory;

class m241226_071602_books_table_seed extends Migration
{
    public function safeUp()
    {
        $faker = Factory::create();

        // Добавляем жанры

        $genres = [];
        for ($i = 0; $i < 3; $i++) {
            $genreName = $faker->word;
            $this->insert('{{%genres}}', ['genre_name' => $genreName]);
            $genres[] = $this->db->getLastInsertId();
        }

        // Добавляем книги с существующими жанрами

        for ($i = 0; $i < 25; $i++) {
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

        $books = $this->db->createCommand('SELECT id FROM {{%books}}')->queryAll();
        $authors = $this->db->createCommand('SELECT id FROM {{%authors}}')->queryAll();

        foreach ($books as $book) {
            $linkCount = rand(1, 2);
            for ($j = 0; $j < $linkCount; $j++) {
                $authorId = $faker->randomElement($authors)['id'];
                $this->insert('{{%links}}', ['book_id' => $book['id'], 'author_id' => $authorId]);
            }
        }
    }
}
