<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixtures
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData()
    {
        $this->createMany(10, 'admin_user', function ($i) {
            $user = new User();
            $user
                ->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->setTwitterUsername($this->faker->userName)
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    '1q2w3e4r'
                ));

            return $user;
        });

        $this->createMany(10, 'main_user', function ($i) {
            $user = new User();
            $user
                ->setEmail($this->faker->email)
                ->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->setTwitterUsername($this->faker->userName)
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordEncoder->encodePassword(
                    $user,
                    '1q2w3e4r'
                ));


            return $user;
        });
    }
}
