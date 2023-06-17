<?php
namespace App\Entity;

use App\Repository\ParticipationRepository;
use ApiPlatform\Metadata\ApiResource;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Prize;
use App\Entity\Users;
#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
#[ApiResource]

class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "first_name", type: "string", length: 255)]
    private $firstName;

    #[ORM\Column(name: "last_name", type: "string", length: 255)]
    private $lastName;

    #[ORM\Column(type: "string", length: 255)]
    private $address;

    #[ORM\Column(type: "string", length: 20)]
    private $phone;

    #[ORM\Column(type: "string", length: 255)]
    private $email;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Giveaways::class, inversedBy: 'participants')]
    #[ORM\JoinColumn(name: "giveaway_id", referencedColumnName: "id", nullable: false)]
    private $giveaway;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct(Users $user, Giveaways $giveaway)
    {
        $this->user = $user;
        $this->giveaway = $giveaway;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
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

    /**
     * @return Users
     */
    public function getUser(): Users
    {
        return $this->user;
    }

    /**
     * @return Giveaways
     */
    public function getGiveaway(): Giveaways
    {
        return $this->giveaway;
    }
}

