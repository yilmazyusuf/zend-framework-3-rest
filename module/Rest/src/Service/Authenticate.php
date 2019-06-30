<?php

namespace Rest\Service;


use Doctrine\ORM\EntityManager;
use Rest\Entity\User;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;

class Authenticate
{


    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Authenticate constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    /**
     * @param $email
     * @param $password
     * @return Result
     */
    public function authenticate(string $email, string $password) :Result
    {

        $user = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($email);
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                ['Invalid credentials.']);
        }

        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($password, $passwordHash)) {
            return new Result(
                Result::SUCCESS,
                $user,
                ['Authenticated successfully.']);
        }

        return new Result(
            Result::FAILURE_CREDENTIAL_INVALID,
            null,
            ['Invalid credentials.']);
    }
}