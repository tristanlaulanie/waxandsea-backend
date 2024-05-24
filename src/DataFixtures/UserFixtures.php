<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('user');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('$2y$13$AOGwOar5O.1wr6bti6jDtOHjWMwLGGiiwnHjdTKK1dDKXeWSUn9ZW');
        
        $manager->persist($user);

        $user = new User();
        $user->setUsername("Tristan");
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('$2y$13$BWg6yvJbbsYPOZ10oaV8s.XNnTFVWwSUMo/Gb8BeK05JRYTelyUiO');
        
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$QliVZbDGjrnq/.4IWBCG1uGZ1VSFQNLgKQrvOhQzHUE6YVhinyJjm');

        $manager->persist($admin);

        $manager->flush();
    }
}
