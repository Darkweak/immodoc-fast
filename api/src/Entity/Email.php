<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"read_email"}},
 *              "access_control"="is_granted('ROLE_ADMIN')"
 *          },
 *          "put"={
 *              "denormalization_context"={"groups"={"write_email"}},
 *              "access_control"="is_granted('ROLE_ADMIN')"
 *          },
 *          "testing"={
 *              "controller"="App\Action\Notify::test",
 *              "path"="/emails/{id}/test",
 *              "method"="get",
 *              "read"=false,
 *              "output"=false,
 *          },
 *          "testingProd"={
 *              "controller"=App\Action\Notify::class,
 *              "path"="/emails/{id}/send",
 *              "method"="get",
 *              "read"=false,
 *              "output"=false,
 *          },
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     normalizationContext={"groups"={"read_emails"}},
 *     denormalizationContext={"groups"={"write_emails"}}
 * )
 * @ORM\Entity
 */
class Email
{
    use IdTrait;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Groups({"read_email", "write_email", "write_emails"})
     */
    private $content;

    /**
     * @var string
     * @ORM\Column
     * @Groups({"read_email", "read_emails", "write_email", "write_emails"})
     */
    private $name;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
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
}
