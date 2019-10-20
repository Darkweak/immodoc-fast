<?php

namespace App\Entity;

use App\Traits\IdTrait;
use App\Traits\TokenTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CryptedFile
{
    use IdTrait;
    use TokenTrait;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default"="false"})
     */
    private $used = false;

    /**
     * @var Payment
     * @ORM\ManyToOne(targetEntity=Payment::class, inversedBy="cryptedFile")
     */
    private $payment;

    /**
     * @var File
     * @ORM\ManyToOne(targetEntity=File::class, inversedBy="cryptedFile")
     */
    private $file;

    public function isUsed(): bool
    {
        return $this->used;
    }

    public function setUsed(bool $used): self
    {
        $this->used = $used;
        return $this;
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    public function setPayment(Payment $payment): self
    {
        $this->payment = $payment;
        return $this;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;
        return $this;
    }
}
