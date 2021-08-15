<?php

namespace App\Tests\Integration\DataPersister;

use App\DataPersister\UserDataPersister;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\InMemoryUser;

class UserDataPersisterTest extends KernelTestCase
{
    public function testPlainPasswordIsHashed(): void
    {
        $kernel = self::bootKernel();
        $userDataPersister = self::getContainer()->get(UserDataPersister::class);
        $userPasswordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $user = new User('test@mail.com','testPass');
        $this->assertTrue($userDataPersister->supports($user));

        $this->assertNull($user->getPassword());
        $this->assertEquals('testPass',$user->getPlainPassword());

        $originalCount = $entityManager->getRepository(User::class)->count([]);

        $userDataPersister->persist($user);

        $this->assertNull($user->getPlainPassword());
        $this->assertIsString($user->getPassword());
        $this->assertTrue($userPasswordHasher->isPasswordValid($user,'testPass'));
        $this->assertFalse($userPasswordHasher->isPasswordValid($user,'testP4ss'));

        $newCount = $entityManager->getRepository(User::class)->count([]);
        $this->assertEquals($originalCount,$newCount-1);

        $userDataPersister->remove($user);

        $this->assertEquals($originalCount,$entityManager->getRepository(User::class)->count([]));
    }

    public function testUpgradePassword() {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $userDataPersister = self::getContainer()->get(UserDataPersister::class);
        $userPasswordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        $user = new User('testUpgradePassword@example.com','testpass');
        $userDataPersister->persist($user);
        $this->assertTrue($userPasswordHasher->isPasswordValid($user,'testpass'));

        $entityManager->getRepository(User::class)->upgradePassword(
            $user,
            $userPasswordHasher->hashPassword(
                $user,
                'newpass'
            )
        );
        $this->assertFalse($userPasswordHasher->isPasswordValid($user,'testpass'));
        $this->assertTrue($userPasswordHasher->isPasswordValid($user,'newpass'));

        $this->expectException(UnsupportedUserException::class);
        $inMemoryUser = new InMemoryUser('username','password');
        $entityManager->getRepository(User::class)->upgradePassword($inMemoryUser,'testpass');
    }
}
