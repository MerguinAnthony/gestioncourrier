<?php

namespace App\Entity;

use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArchmailRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArchmailRepository::class)]
#[Vich\Uploadable]
class Archmail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un expéditeur')]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $sender = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un objet')]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $object = null;

    #[Vich\UploadableField(mapping: 'mail', fileNameProperty: 'fileName1')]
    private ?File $fileFile1 = null;

    #[ORM\Column(nullable: true)]
    private ?string $fileName1 = null;

    #[Vich\UploadableField(mapping: 'mail', fileNameProperty: 'fileName2')]
    private ?File $fileFile2 = null;

    #[ORM\Column(nullable: true)]
    private ?string $fileName2 = null;

    #[Vich\UploadableField(mapping: 'mail', fileNameProperty: 'fileName3')]
    private ?File $fileFile3 = null;

    #[ORM\Column(nullable: true)]
    private ?string $fileName3 = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function setSender(string $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): static
    {
        $this->object = $object;

        return $this;
    }

    public function setFileFile1(?File $fileFile1 = null): void
    {
        $this->fileFile1 = $fileFile1;

        if (null !== $fileFile1) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFileFile1(): ?File
    {
        return $this->fileFile1;
    }

    public function setFileName1(?string $fileName1): void
    {
        $this->fileName1 = $fileName1;
    }

    public function getFileName1(): ?string
    {
        return $this->fileName1;
    }

    public function setFileFile2(?File $fileFile2 = null): void
    {
        $this->fileFile2 = $fileFile2;

        if (null !== $fileFile2) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFileFile2(): ?File
    {
        return $this->fileFile2;
    }

    public function setFileName2(?string $fileName2): void
    {
        $this->fileName2 = $fileName2;
    }

    public function getFileName2(): ?string
    {
        return $this->fileName2;
    }

    public function setFileFile3(?File $fileFile3 = null): void
    {
        $this->fileFile3 = $fileFile3;

        if (null !== $fileFile3) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFileFile3(): ?File
    {
        return $this->fileFile3;
    }

    public function setFileName3(?string $fileName3): void
    {
        $this->fileName3 = $fileName3;
    }

    public function getFileName3(): ?string
    {
        return $this->fileName3;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
