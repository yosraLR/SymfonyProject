<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ParticipationRepository::class)]

class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'participations')]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id" , nullable: false)]
    private $user;

   
    #[ORM\ManyToOne(targetEntity: Giveaways::class, inversedBy: 'participants')]
    #[ORM\JoinColumn(name: "giveaway_id", referencedColumnName: "id" , nullable: false)]
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
