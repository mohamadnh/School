<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClasseRepository::class)
 */
class Classe
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
    private $section;

    /**
     * @ORM\OneToMany(targetEntity=StudentsGrades::class, mappedBy="classe")
     */
    private $studentsGrades;

    /**
     * @ORM\OneToMany(targetEntity=Course::class, mappedBy="classe")
     */
    private $course;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="classe")
     */
    private $student;

    public function __construct()
    {
        $this->studentsGrades = new ArrayCollection();
        $this->course = new ArrayCollection();
        $this->student = new ArrayCollection();
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

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): self
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourse(): Collection
    {
        return $this->course;
    }

    public function addCourse(Course $course): self
    {
        if (!$this->course->contains($course)) {
            $this->course[] = $course;
            $course->setClasse($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        if ($this->course->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getClasse() === $this) {
                $course->setClasse(null);
            }
        }

        return $this;
    }
    public function __toString(): string{
        return $this->getId();
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudent(): Collection
    {
        return $this->student;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->student->contains($student)) {
            $this->student[] = $student;
            $student->setClasse($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->student->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClasse() === $this) {
                $student->setClasse(null);
            }
        }

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
            $studentsGrade->setClasse($this);
        }

        return $this;
    }

    public function removeStudentsGrade(StudentsGrades $studentsGrade): self
    {
        if ($this->studentsGrades->removeElement($studentsGrade)) {
            // set the owning side to null (unless already changed)
            if ($studentsGrade->getClasse() === $this) {
                $studentsGrade->setClasse(null);
            }
        }

        return $this;
    }
}
