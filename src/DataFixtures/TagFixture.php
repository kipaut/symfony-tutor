<?php

namespace App\DataFixtures;

use App\Entity\Tag;

class TagFixture extends BaseFixtures
{
    protected function loadData()
    {
        $this->createMany(Tag::class, 10, function(Tag $tag) {
            $tag->setName($this->faker->realText(20));
        });
    }

}
