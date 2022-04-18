<?php

namespace App\Entity;

use App\Repository\StudentsGradesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentsGradesRepository::class)
 */
class StudentsGrades
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="course")
     */
    private $student;

    /**
     * @ORM\ManyToOne(targetEntity=Course::class, inversedBy="studentsGrades")
     */
    private $course;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="studentsGrades")
     */
    private $classe;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $grade;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
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

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function getImage(): ?string
    {
        if($this->student != null)
            return $this->student->getImage();
        return null;
    }

    public function getFirstName(): ?string
    {
        if($this->student != null)
            return $this->student->getFirstName();
            return null;
    }

    public function getLastName(): ?string
    {
        if($this->student != null)
            return $this->student->getLastName();
            return null;
    }

    public function getClasseName(): ?string
    {
        if($this->student != null)
            return $this->student->getClasseName();
        return null;
    }

    public function getCourseName(): ?string
    {
        if($this->course != null)
            return $this->course->getName();
        return null;
    }

    public function setGrade(string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }
}
