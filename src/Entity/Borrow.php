<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BorrowRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BorrowRepository::class)
 * @ApiResource()
 */
class Borrow
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $borrowedAt;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $returnedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBack;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="borrows")
     */
    private $books;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="borrows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorrowedAt(): ?\DateTimeInterface
    {
        return $this->borrowedAt;
    }

    public function setBorrowedAt(\DateTimeInterface $borrowedAt): self
    {
        $this->borrowedAt = $borrowedAt;

        return $this;
    }

    public function getReturnedAt(): ?\DateTimeInterface
    {
        return $this->returnedAt;
    }

    public function setReturnedAt(\DateTimeInterface $returnedAt): self
    {
        $this->returnedAt = $returnedAt;

        return $this;
    }


    public function getIsBack(): ?bool
    {
        return $this->isBack;
    }

    public function setIsBack(bool $isBack): self
    {
        $this->isBack = $isBack;

        return $this;
    }

    public function getBooks(): ?Book
    {
        return $this->books;
    }

    public function setBooks(?Book $books): self
    {
        $this->books = $books;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function timeleft($borrowedAt,$returnedAt){
        $diff = strtotime($borrowedAt)-strtotime($returnedAt);
        return ceil(abs($diff/86400));
    }
}
