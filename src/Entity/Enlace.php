<?php

namespace App\Entity;

use App\Repository\EnlaceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnlaceRepository::class)
 */
class Enlace
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idUsuario;

    /**
     * @ORM\Column(type="integer")
     */
    private $idContacto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    }

    public function setIdUsuario(int $idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    public function getIdContacto(): ?int
    {
        return $this->idContacto;
    }

    public function setIdContacto(int $idContacto): self
    {
        $this->idContacto = $idContacto;

        return $this;
    }
}
