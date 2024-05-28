<?php

namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
class Registration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\Email]
    private $email;

    #[ORM\Column(type: 'string', length: 15)]
    #[Assert\NotBlank]
    private $telephone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $street;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $streetnumber;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $town;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank]
    private $zipcode;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $country;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $password;

    // Getters et setters...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetnumber(): ?int
    {
        return $this->streetnumber;
    }

    public function setStreetnumber(int $streetnumber): self
    {
        $this->streetnumber = $streetnumber;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
