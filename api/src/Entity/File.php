<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Traits\IdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *          "put"={"access_control"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     normalizationContext={"groups"={"read_file"}},
 *     denormalizationContext={"groups"={"read_file"}}
 * )
 * @ORM\Entity
 */
class File
{
    use IdTrait;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Groups({"read_file"})
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"read_file"})
     */
    private $name;

    /**
     * @var string
     * @ORM\Column
     */
    private $path;

    /**
     * @var double
     * @ORM\Column(type="float")
     * @Groups({"read_file"})
     */
    private $price;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity=CryptedFile::class, mappedBy="file")
     */
    private $cryptedFiles;

    public function __construct()
    {
        $this->cryptedFiles = new ArrayCollection();
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
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
