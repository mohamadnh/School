<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseRepository::class)
 */
class Course
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=StudentsGrades::class, mappedBy="course")
     */
    private $studentsGrades;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="course")
     */
    private $classe;

    public function __construct()
    {
        $this->studentsGrades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, StudentsGrades>
     */
    public function getStudentsGrades(): Collection
    {
        return $this->studentsGrades;
    }

    public function addStudentsGrade(StudentsGrades $studentsGrade): self
    {
        if (!$this->studentsGrades->contains($studentsGrade)) {
            $this->studentsGrades[] = $studentsGrade;
            $studentsGrade->setCourse($this);
        }

        return $this;
    }

    public function removeStudentsGrade(StudentsGrades $studentsGrade): self
    {
        if ($this->studentsGrades->removeElement($studentsGrade)) {
            // set the owning side to null (unless already changed)
            if ($studentsGrade->getCourse() === $this) {
                $studentsGrade->setCourse(null);
            }
        }

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function getClasseName(): ?string
    {
        if($this->classe != null)
            return $this->classe->getName();
        return null;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }
    public function __toString(): string{
        return $this->getId();
    }
}
