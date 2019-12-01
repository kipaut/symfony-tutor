<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends BaseFixtures implements DependentFixtureInterface
{
    public function loadData()
    {
        $this->createMany(Comment::class, 100, function (Comment $comment, $count) {
            $comment
                ->setAuthorName($this->faker->name)
                ->setContent($this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 days', '-1 seconds'))
                ->setIsDeleted($this->faker->boolean(20))
                ->setArticle($this->getRandomReference(Article::class));
        });
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            ArticleFixtures::class,
        ];
    }
}
