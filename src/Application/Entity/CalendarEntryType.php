<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CalendarEntryType
 *
 * @ORM\Table(name="calendar_entry_type")
 * @ORM\Entity(repositoryClass="Application\Repository\CalenradEntryTypeRepository")
 */
class CalendarEntryType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="entry_type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $entryTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="entry_name", type="string", length=255, nullable=false)
     */
    private $entryName;

    /**
     * @var string
     *
     * @ORM\Column(name="entry_formula", type="string", length=255, nullable=true)
     */
    private $entryFormula;



    /**
     * Get entryTypeId
     *
     * @return integer 
     */
    public function getEntryTypeId()
    {
        return $this->entryTypeId;
    }

    /**
     * Set entryName
     *
     * @param string $entryName
     * @return CalendarEntryType
     */
    public function setEntryName($entryName)
    {
        $this->entryName = $entryName;

        return $this;
    }

    /**
     * Get entryName
     *
     * @return string 
     */
    public function getEntryName()
    {
        return $this->entryName;
    }

    /**
     * Set entryFormula
     *
     * @param string $entryFormula
     * @return CalendarEntryType
     */
    public function setEntryFormula($entryFormula)
    {
        $this->entryFormula = $entryFormula;

        return $this;
    }

    /**
     * Get entryFormula
     *
     * @return string 
     */
    public function getEntryFormula()
    {
        return $this->entryFormula;
    }
}
