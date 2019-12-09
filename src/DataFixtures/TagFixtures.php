<?php

namespace App\DataFixtures;

use App\Entity\Tag;

class TagFixtures extends BaseFixtures
{
    protected function loadData()
    {
        $this->createMany(
            10,
            'tag',
            function ($i) {
                $tag = new Tag();
                $tag->setName($this->faker->realText(20));

                return $tag;
            }
        );
    }

}
