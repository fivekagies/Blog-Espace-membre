<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadPosts($manager);
    }
    //load Posts
    public function loadPosts(ObjectManager $manager)
    {
        $slugify= new Slugify();
        for ($i=0;$i<20;$i++)
        {
            $post = new Post();
            $post->setTitle('this is my first title '.rand(0,100));
            $post->setBody('this is my first body '.rand(0,100));
            $post->setTime(new \DateTime());
            $post->setUser($this->getReference('five'));
            $post->setSlug($slugify->slugify($post->getTitle()));
            $manager->persist($post);
        }

        $manager->flush();
    }

    //load Users
    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('five');
        $user->setFullname('fivekagies');
        $user->setEmail('fivekagies@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'punch123'));

        $this->addReference('five',$user); // relation du user avec poste
        $manager->persist($user);

        $manager->flush();
    }
}
