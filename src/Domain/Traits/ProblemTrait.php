<?php

namespace Clean\Common\Domain\Traits;

use Clean\Common\Utils\Extensions\ArrayObject\ArrayObject;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItem;
use Clean\Common\Utils\Extensions\ArrayObject\ArrayObjectItemInterface;

trait ProblemTrait
{
    /**
     * @var ArrayObject|null
     */
    protected $problems;
    protected $defaultMaxProblemsPerObject = 3;

    /**
     * @param ArrayObjectItemInterface $problem
     * @return void
     */
    public function addProblemItem(mixed $problem)
    {
        if ($this->problems === null) {
            $this->problems = new ArrayObject(true);
        }

        if (!$problem instanceof ArrayObjectItem) {
            $problem = new ArrayObjectItem($problem);
        }

        $this->clampProblems(getenv('MAX_PROBLEMS_PER_OBJECT') ?: $this->defaultMaxProblemsPerObject);

        $problems = $this->getProblems();
        $problems->addItem($problem);
        $this->setProblems($problems);
    }

    public function clampProblems(int $maxProblemsSize) {
        if ($this->problems === null) {
            $this->problems = new ArrayObject(true);
        }

        $problems = $this->getProblems();
        $problemsCount = $problems->count();

        $problemsArray = $problems->toArray();
        $newProblems = new ArrayObject(true);

        $deleteRowsCount = $problemsCount - $maxProblemsSize;
        for ($i = 0 ; $i < $deleteRowsCount ; $i++) {
            array_shift($problemsArray);
        }
        foreach($problemsArray as $problemItem) {
            $arrayObjectProblemItem = new ArrayObjectItem($problemItem);
            $newProblems->addItem($arrayObjectProblemItem);
        }

        $this->setProblems($newProblems);
    }

    /**
     * @param $problem
     * @return bool
     */
    public function hasProblemItem($problem): bool
    {
        if (isset($this->problems)) {
            return $this->problems->hasItem($problem);
        }

        return false;
    }

    /**
     * @return ArrayObject|null
     */
    public function getProblems(): ?ArrayObject
    {
        return $this->problems;
    }

    /**
     * @param ArrayObject|null $problems
     */
    public function setProblems(?ArrayObject $problems): void
    {
        $this->problems = $problems;
    }
}