<?php

namespace Clean\Common\Application\Interfaces;

use Clean\Common\Domain\Entities\EntityAbstract;

interface RepositoryInterface
{
    public function getById($id);

    public function delete($id);
}