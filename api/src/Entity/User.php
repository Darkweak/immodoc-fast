<?php

namespace App\Entity;

use App\Traits\EmailTrait;
use App\Traits\IdTrait;
use App\Traits\TokenTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class User
{
    use IdTrait;
    use EmailTrait;
    use TokenTrait;

    /**
     * @var string
     * @ORM\Column
     */
    private $password;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}
