<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 * @Vich\Uploadable()
 */
class Student
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="students", fileNameProperty="image")
     */
    private $imageFile;
    /**
     * @ORM\OneToMany(targetEntity=StudentsGrades::class, mappedBy="student")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="student")
     */
    private $classe;

    public function __construct()
    {
        $this->course = new ArrayCollection();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;

        if($imageFile){
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getClasseName(): ?string
    {
        if($this->classe != null)
            return $this->classe->getName();
        return null;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * @return Collection<int, StudentsGrades>
     */
    public function getCourse(): Collection
    {
        return $this->course;
    }

    public function addCourse(StudentsGrades $course): self
    {
        if (!$this->course->contains($course)) {
            $this->course[] = $course;
            $course->setStudent($this);
        }

        return $this;
    }

    public function removeCourse(StudentsGrades $course): self
    {
        if ($this->course->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getStudent() === $this) {
                $course->setStudent(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    public function __toString(){
        return $this->getName();
    }
}
