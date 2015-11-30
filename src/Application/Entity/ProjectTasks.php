<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectTasks
 *
 * @ORM\Table(name="project_tasks", indexes={@ORM\Index(name="pt_dependency_fk", columns={"project_dependence"}), @ORM\Index(name="pt_project_fk", columns={"project_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\ProjectTasksRepository")
 */
class ProjectTasks
{
    /**
     * @var integer
     *
     * @ORM\Column(name="task_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $taskId;

    /**
     * @var string
     *
     * @ORM\Column(name="task_name", type="text", length=65535, nullable=false)
     */
    private $taskName;

    /**
     * @var string
     *
     * @ORM\Column(name="task_description", type="text", length=65535, nullable=true)
     */
    private $taskDescription;

    /**
     * @var \Application\Entity\ProjectList
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\ProjectList")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_dependence", referencedColumnName="project_id")
     * })
     */
    private $projectDependence;

    /**
     * @var \Application\Entity\ProjectList
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\ProjectList")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="project_id")
     * })
     */
    private $project;



    /**
     * Get taskId
     *
     * @return integer 
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * Set taskName
     *
     * @param string $taskName
     * @return ProjectTasks
     */
    public function setTaskName($taskName)
    {
        $this->taskName = $taskName;

        return $this;
    }

    /**
     * Get taskName
     *
     * @return string 
     */
    public function getTaskName()
    {
        return $this->taskName;
    }

    /**
     * Set taskDescription
     *
     * @param string $taskDescription
     * @return ProjectTasks
     */
    public function setTaskDescription($taskDescription)
    {
        $this->taskDescription = $taskDescription;

        return $this;
    }

    /**
     * Get taskDescription
     *
     * @return string 
     */
    public function getTaskDescription()
    {
        return $this->taskDescription;
    }

    /**
     * Set projectDependence
     *
     * @param \Application\Entity\ProjectList $projectDependence
     * @return ProjectTasks
     */
    public function setProjectDependence(\Application\Entity\ProjectList $projectDependence = null)
    {
        $this->projectDependence = $projectDependence;

        return $this;
    }

    /**
     * Get projectDependence
     *
     * @return \Application\Entity\ProjectList 
     */
    public function getProjectDependence()
    {
        return $this->projectDependence;
    }

    /**
     * Set project
     *
     * @param \Application\Entity\ProjectList $project
     * @return ProjectTasks
     */
    public function setProject(\Application\Entity\ProjectList $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Application\Entity\ProjectList 
     */
    public function getProject()
    {
        return $this->project;
    }
}
