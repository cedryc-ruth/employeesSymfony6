<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\Table('employees')]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: '`emp_no`', type: 'string')]
    private ?string $id = null;

    #[ORM\Column(length: 14)]
    private ?string $first_name = null;

    #[ORM\Column(length: 16)]
    private ?string $last_name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birth_date = null;

    #[ORM\Column(length: 1)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $hire_date = null;

    #[ORM\JoinTable(name: 'dept_manager')]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    #[ORM\InverseJoinColumn(name: 'dept_no', referencedColumnName: 'dept_no')]
    #[ORM\ManyToMany(targetEntity: Department::class, mappedBy: 'manager')]
    private Collection $departments;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: DeptManager::class)]
    #[ORM\JoinColumn(name: 'emp_no', referencedColumnName: 'emp_no')]
    private Collection $managingStories;

    public function __construct()
    {
        $this->departments = new ArrayCollection();
        $this->managingStories = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getHireDate(): ?\DateTimeInterface
    {
        return $this->hire_date;
    }

    public function setHireDate(\DateTimeInterface $hire_date): self
    {
        $this->hire_date = $hire_date;

        return $this;
    }

    /**
     * @return Collection<int, Department>
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->departments->contains($department)) {
            $this->departments->add($department);
            $department->addManager($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->departments->removeElement($department)) {
            $department->removeManager($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, DeptManager>
     */
    public function getManagingStories(): Collection
    {
        return $this->managingStories;
    }

    public function addManagingStory(DeptManager $managingStory): self
    {
        if (!$this->managingStories->contains($managingStory)) {
            $this->managingStories->add($managingStory);
            $managingStory->setEmployee($this);
        }

        return $this;
    }

    public function removeManagingStory(DeptManager $managingStory): self
    {
        if ($this->managingStories->removeElement($managingStory)) {
            // set the owning side to null (unless already changed)
            if ($managingStory->getEmployee() === $this) {
                $managingStory->setEmployee(null);
            }
        }

        return $this;
    }
}
