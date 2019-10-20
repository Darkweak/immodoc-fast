<?php

namespace App\Entity;

use App\Traits\EmailTrait;
use App\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Payment
{
    use IdTrait;
    use EmailTrait;

    /**
     * @var string
     * @ORM\Column
     */
    private $firstname;

    /**
     * @var string
     * @ORM\Column
     */
    private $lastname;

    /**
     * @var \DateTime
     * @ORM\Column
     */
    private $transactionDate;

    /**
     * @var int
     * @ORM\Column
     */
    private $amount;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity=CryptedFile::class, mappedBy="file")
     */
    private $cryptedFiles;

    public function __construct()
    {
        $this->cryptedFiles = new ArrayCollection();
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getTransactionDate(): \DateTime
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(\DateTime $transactionDate): self
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getCryptedFiles(): Collection
    {
        return $this->cryptedFiles;
    }

    public function setCryptedFiles(Collection $cryptedFiles): self
    {
        $this->cryptedFiles = $cryptedFiles;
        return $this;
    }

    public function addCryptedFile(File $file): self
    {
        if (!$this->cryptedFiles->contains($file)) {
            $this->cryptedFiles->add($file);
        }
        return $this;
    }

    public function removeCryptedFile(File $file): self
    {
        $this->cryptedFiles->removeElement($file);
        return $this;
    }
}
