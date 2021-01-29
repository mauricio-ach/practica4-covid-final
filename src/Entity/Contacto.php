<?php

namespace App\Entity;

use App\Repository\ContactoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContactoRepository::class)
 */
class Contacto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $edad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexo;

    /**
     * @ORM\Column(type="float")
     */
    private $pContagio;

    /**
     * @ORM\Column(type="float")
     */
    private $pContacto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): self
    {
        $this->edad = $edad;

        return $this;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }

    public function setSexo(string $sexo): self
    {
        $this->sexo = $sexo;

        return $this;
    }

    public function getPContagio(): ?float
    {
        return $this->pContagio;
    }

    public function setPContagio(float $pContagio): self
    {
        $this->pContagio = $pContagio;

        return $this;
    }

    public function getPContacto(): ?float
    {
        return $this->pContacto;
    }

    public function setPContacto(float $pContacto): self
    {
        $this->pContacto = $pContacto;

        return $this;
    }
}
