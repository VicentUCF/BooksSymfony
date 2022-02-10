<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
	#[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private $id;

	#[ORM\Column(type: 'string', length: 255)]
  #[Assert\NotBlank]
  private $title;

	#[ORM\Column(type: 'date')]
  #[Assert\NotBlank]
  private $date;

  #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'books')]
  #[ORM\JoinColumn(nullable: false)]
  #[Assert\NotBlank]
  private $author;

  #[ORM\ManyToOne(targetEntity: Genere::class, inversedBy: 'books')]
  #[ORM\JoinColumn(nullable: false)]
  #[Assert\NotBlank]
  private $genere;

	public function getId(): ?int
                  	{
                  		return $this->id;
                  	}

	public function getTitle(): ?string
                  	{
                  		return $this->title;
                  	}

	public function setTitle(string $title): self
                  	{
                  		$this->title = $title;

                  		return $this;
                  	}

	public function getDate(): ?\DateTimeInterface
                  	{
                  		return $this->date;
                  	}

	public function setDate(\DateTimeInterface $date): self
                  	{
                  		$this->date = $date;

                  		return $this;
                  	}

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    function __toString()
    {
      return $this->title;
    }

    public function getGenere(): ?Genere
    {
        return $this->genere;
    }

    public function setGenere(?Genere $genere): self
    {
        $this->genere = $genere;

        return $this;
    }
}
