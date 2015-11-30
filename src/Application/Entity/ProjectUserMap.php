<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectUserMap
 *
 * @ORM\Table(name="project_user_map", indexes={@ORM\Index(name="pum_project_fk", columns={"project_id"}), @ORM\Index(name="pum_user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="Application\Repository\ProjectUserMapRepository")
 */
class ProjectUserMap
{
    /**
     * @var integer
     *
     * @ORM\Column(name="pu_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $puId;

    /**
     * @var integer
     *
     * @ORM\Column(name="access_level", type="integer", nullable=false)
     */
    private $accessLevel;

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
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;



    /**
     * Get puId
     *
     * @return integer 
     */
    public function getPuId()
    {
        return $this->puId;
    }

    /**
     * Set accessLevel
     *
     * @param integer $accessLevel
     * @return ProjectUserMap
     */
    public function setAccessLevel($accessLevel)
    {
        $this->accessLevel = $accessLevel;

        return $this;
    }

    /**
     * Get accessLevel
     *
     * @return integer 
     */
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }

    /**
     * Set project
     *
     * @param \Application\Entity\ProjectList $project
     * @return ProjectUserMap
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

    /**
     * Set user
     *
     * @param \Application\Entity\User $user
     * @return ProjectUserMap
     */
    public function setUser(\Application\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
