<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordDataPersister implements DataPersisterInterface
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function supports($data): bool
    {
        return $data instanceof User and $data->getPlainPassword() !== null;
    }

    /**
     * @param User $data
     * @return void
     */
    public function persist($data)
    {
        $data->setPassword(
            $this->passwordHasher->hashPassword(
                $data,
                $data->getPlainPassword()
            )
        );

        $data->eraseCredentials();

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
