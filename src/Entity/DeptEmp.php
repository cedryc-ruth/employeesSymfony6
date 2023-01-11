<?php

namespace App\Entity;

use App\Repository\DeptEmpRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeptEmpRepository::class)]
class DeptEmp
{
    #[ORM\Id]
    #[ORM\Column(name:'emp_no')]
    public ?Employee $employee = null;

    #[ORM\Id]
    #[ORM\Column(name:'dept_no', length: 4)]
    public ?Department $department = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $from_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $to_date = null;

    #[ORM\ManyToOne(inversedBy: 'deptStories')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no',nullable: false)]
    private ?Employee $employeeFull = null;

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->from_date;
    }

    public function setFromDate(\DateTimeInterface $from_date): self
    {
        $this->from_date = $from_date;

        return $this;
    }

    public function getToDate(): ?\DateTimeInterface
    {
        return $this->to_date;
    }

    public function setToDate(\DateTimeInterface $to_date): self
    {
        $this->to_date = $to_date;

        return $this;
    }

    public function getEmployeeFull(): ?Employee
    {
        return $this->employeeFull;
    }

    public function setEmployeeFull(?Employee $employee): self
    {
        $this->employeeFull = $employee;

        return $this;
    }
}
