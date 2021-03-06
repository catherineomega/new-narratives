<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Nines\UtilBundle\Entity\AbstractEntity;

define('CIRCA_RE', "(c?)([1-9][0-9]{3})");
define('YEAR_RE', '/^' . CIRCA_RE . '$/');
define('RANGE_RE', '/^(?:' . CIRCA_RE . ')?-(?:' . CIRCA_RE . ')?$/');

/**
 * Date
 *
 * @ORM\Table(name="date_year")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DateYearRepository")
 */
class DateYear extends AbstractEntity {

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     */
    private $start;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $startCirca;

    /**
     * @var boolean
     * @ORM\Column(type="integer", nullable=true)
     */
    private $end;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $endCirca;

    /**
     * @var DateCategory
     * @ORM\ManyToOne(targetEntity="DateCategory", inversedBy="dates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dateCategory;

    /**
     * @var Work
     * @ORM\ManyToOne(targetEntity="Work", inversedBy="dates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $work;

    public function __construct() {
        parent::__construct();
        $this->start = null;
        $this->startCirca = false;
        $this->end = null;
        $this->endCirca = false;
    }
    
    /**
     * Return a string representation.
     * 
     * @return string
     */
    public function __toString() {
        if (($this->startCirca === $this->endCirca) && ($this->start === $this->end)) {
            return ($this->startCirca ? 'c' : '') . $this->start;
        }
        return ($this->startCirca ? 'c' : '') . $this->start .
                '-' .
                ($this->endCirca ? 'c' : '') . $this->end;
    }

    public function getValue() {
        return (string) $this;
    }

    public function setValue($value) {
        $value = strtolower(preg_replace('/\s*/', '', (string)$value));
        $matches = array();
        if (strpos($value, '-') === false) {
            // not a range
            if (preg_match(YEAR_RE, $value, $matches)) {
                $this->startCirca = ($matches[1] === 'c');
                $this->start = $matches[2];
                $this->endCirca = $this->startCirca;
                $this->end = $this->start;
            } else {
                throw new Exception("Malformed date {$value}");
            }
            return $this;
        }
        if (!preg_match(RANGE_RE, $value, $matches)) {
            throw new Exception("Malformed Date range '{$value}'");
        }
        
            $this->startCirca = ($matches[1] === 'c');
            $this->start = $matches[2];
        if (count($matches) > 3) {
            $this->endCirca = ($matches[3] === 'c');
            $this->end = $matches[4];
        }
        return $this;
    }
    
    public function isRange() {
        return 
            ($this->startCirca !== $this->endCirca) ||
            ($this->start !== $this->end);
       
    }
    
    public function hasStart() {
        return $this->start !== null && $this->start !== '';
    }

    /**
     * Get start
     *
     * @return integer
     */
    public function getStart() {
        return ($this->startCirca ? 'c' : '') . $this->start;
    }

    public function hasEnd() {
        return $this->end !== null && $this->end !== '';
    }
    
    /**
     * Get end
     *
     * @return integer
     */
    public function getEnd() {
        return ($this->endCirca ? 'c' : '') . $this->end;
    }

    /**
     * Set dateCategory
     *
     * @param DateCategory $dateCategory
     *
     * @return DateYear
     */
    public function setDateCategory(DateCategory $dateCategory) {
        $this->dateCategory = $dateCategory;

        return $this;
    }

    /**
     * Get dateCategory
     *
     * @return DateCategory
     */
    public function getDateCategory() {
        return $this->dateCategory;
    }

    /**
     * Set work
     *
     * @param Work $work
     *
     * @return DateYear
     */
    public function setWork(Work $work) {
        $this->work = $work;

        return $this;
    }

    /**
     * Get work
     *
     * @return Work
     */
    public function getWork() {
        return $this->work;
    }

}
