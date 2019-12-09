<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ApiTokenFixtures extends BaseFixtures implements DependentFixtureInterface
{
    protected function loadData()
    {
        $this->createMany(
            20,
            'api_token',
            function ($i) {
                $apiToken = new ApiToken($this->getRandomReference('main_user'));

                return $apiToken;
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
        ];
    }
}
