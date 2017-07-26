<?php

namespace Application\Entity;

/**
 * ProjectList
 *
 * @ORM\Table(name="project_list")
 * @ORM\Entity(repositoryClass="Application\Repository\ProjectListRepository")
 */
class ProjectList
{
    /**
     * @var integer
     *
     * @ORM\Column(name="project_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $projectId;

    /**
     * @var string
     *
     * @ORM\Column(name="project_name", type="text", length=65535, nullable=false)
     */
    private $projectName;
    /**
     * @var integer
     *
     * @ORM\Column(name="ordering", type="integer")
     */
    private $ordering;
    /**
     * @var string
     *
     * @ORM\Column(name="project_description", type="text", length=65535, nullable=true)
     */
    private $projectDescription;



    /**
     * Get projectId
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set projectName
     *
     * @param string $projectName
     * @return ProjectList
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * Get projectName
     *
     * @return string 
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Set projectDescription
     *
     * @param string $projectDescription
     * @return ProjectList
     */
    public function setProjectDescription($projectDescription)
    {
        $this->projectDescription = $projectDescription;

        return $this;
    }

    /**
     * Get projectDescription
     *
     * @return string 
     */
    public function getProjectDescription()
    {
        return $this->projectDescription;
    }
}
