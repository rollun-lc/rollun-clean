<?php

namespace Clean\Common\Application\Interfaces;

/**
 * @todo Зробити специфічні інтерфейси
 */
interface RepositoryInterface
{
    public function getById($id);

    public function delete($id);
}