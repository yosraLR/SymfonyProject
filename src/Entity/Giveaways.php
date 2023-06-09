<?php

namespace App\Entity;

use App\Repository\GiveawaysRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GiveawaysRepository::class)]
class Giveaways
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $StartDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $EndDate = null;

    #[ORM\ManyToOne(inversedBy: 'giveaways')]
    #[ORM\JoinColumn(name: "organisator_id", referencedColumnName: "id" , nullable: false)]
    private ?Users $OrganisatorID = null;

    #[ORM\OneToMany(mappedBy: 'giveaways', targetEntity: Prize::class)]
    private Collection $PrizeID;

    #[ORM\OneToMany(mappedBy: 'giveaways', targetEntity: "App\Entity\Participation", cascade: ["persist", "remove"])]
    private Collection $participants;

    #[ORM\Column(nullable: true)]
    private ?int $winner = null;

    public function __construct()
    {
        $this->PrizeID = new ArrayCollection();
        $this->participants = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }


    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->StartDate;
    }

    public function setStartDate(\DateTimeInterface $StartDate): self
    {
        $this->StartDate = $StartDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->EndDate;
    }

    public function setEndDate(\DateTimeInterface $EndDate): self
    {
        $this->EndDate = $EndDate;

        return $this;
    }

    public function getOrganisatorID(): ?Users
    {
        return $this->OrganisatorID;
    }

    public function setOrganisatorID(?Users $OrganisatorID): self
    {
        $this->OrganisatorID = $OrganisatorID;

        return $this;
    }

    /**
     * @return Collection<int, Prize>
     */
    public function getPrizeID(): Collection
    {
        return $this->PrizeID;
    }

    public function addPrizeID(Prize $prizeID): self
    {
        if (!$this->PrizeID->contains($prizeID)) {
            $this->PrizeID->add($prizeID);
            $prizeID->setGiveaways($this);
        }

        return $this;
    }

    public function removePrizeID(Prize $prizeID): self
    {
        if ($this->PrizeID->removeElement($prizeID)) {
            if ($prizeID->getGiveaways() === $this) {
                $prizeID->setGiveaways(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Users $user): self
    {
        if (!$this->participants->contains($user)) {
            $this->participants[] = $user;
            $user->addParticipation($this);
        }

        return $this;
    }

    public function removeParticipant(Users $user): self
    {
        if ($this->participants->removeElement($user)) {
            $user->removeParticipation($this);
        }

        return $this;
    }

    public function getWinner(): ?int
    {
        return $this->winner;
    }

    public function setWinner(?int $winner): self
    {
        $this->winner = $winner;
        return $this;
    }
}