<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testConstructorDefaultValues(): void
    {
        $user = new User('test@example.com','plain_password3');

        $this->assertNull($user->getId());
        $this->assertEquals('test@example.com',$user->getEmail());
        $this->assertNull($user->getPassword());
        $this->assertEquals('plain_password3',$user->getPlainPassword());
        $this->assertEquals(['ROLE_USER'],$user->getRoles());
        $this->assertNull($user->getSalt());
    }

    public function testEraseCredentials(): void
    {
        $user = new User('test@example.com','plain_password3');

        $this->assertEquals('plain_password3',$user->getPlainPassword());
        $user->eraseCredentials();
        $this->assertNull($user->getPlainPassword());
    }

    public function testRoles(): void
    {
        $user = new User('test@example.com','plain_password3');

        $this->assertEquals(['ROLE_USER'],$user->getRoles());

        $user->setRoles(['ROLE_USER','ROLE_TEST']);
        $this->assertEquals(['ROLE_USER','ROLE_TEST'],$user->getRoles());

        $user->setRoles(['ROLE_TEST']);
        $this->assertEquals(['ROLE_TEST','ROLE_USER'],$user->getRoles());
    }

    public function testUserIdentifier() {
        $user = new User('test@example.com','plain_password3');

        $this->assertEquals('test@example.com',$user->getUsername());
        $this->assertEquals('test@example.com',$user->getUserIdentifier());
        $this->assertEquals('test@example.com',$user->getEmail());

        $user->setEmail('test_email@example.com');

        $this->assertEquals('test_email@example.com',$user->getUsername());
        $this->assertEquals('test_email@example.com',$user->getUserIdentifier());
        $this->assertEquals('test_email@example.com',$user->getEmail());
    }
}
