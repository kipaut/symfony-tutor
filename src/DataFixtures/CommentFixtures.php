<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends BaseFixtures implements DependentFixtureInterface
{
    protected function loadData()
    {
        $this->createMany(
            '100',
            'comment',
            function ($i) {
                $comment = new Comment();
                $comment
                    ->setAuthor($this->getRandomReference('main_user'))
                    ->setContent($this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true))
                    ->setCreatedAt($this->faker->dateTimeBetween('-1 days', '-1 seconds'))
                    ->setIsDeleted($this->faker->boolean(20))
                    ->setArticle($this->getRandomReference('article'));

                return $comment;
            }
        );
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ArticleFixtures::class,
        ];
    }
}
