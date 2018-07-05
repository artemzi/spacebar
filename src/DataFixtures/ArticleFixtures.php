<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures
{
    private static $articleImages = [
        'asteroid.jpeg',
        'mercury.jpeg',
        'lightspeed.png',
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Article::class, 10, function(Article $article, $count) use ($manager) {
            $article->setTitle($this->faker->sentence($nbWords = 6, $variableNbWords = true))
                ->setContent($this->faker->realText($maxNbChars = 1500, $indexSize = 2));

            // publish most articles
            if ($this->faker->boolean(70)) {
                $article->setPublishedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            }

            $article->setAuthor($this->faker->name($gender = 'male'|'female'))
                ->setHeartCount($this->faker->numberBetween(5, 100))
                ->setImageFilename($this->faker->randomElement(self::$articleImages));
        });

        $manager->flush();
    }
}
