<?php

namespace Clean\Common\Application\Interfaces;

interface RepositoryInterface
{
    public function getById($id);

    public function delete($id);
}