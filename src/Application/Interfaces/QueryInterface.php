<?php

namespace Clean\Common\Application\Interfaces;

interface QueryInterface
{
    public function getSelect();

    public function setSelect($select);

    public function getQuery();

    public function setQuery($query);

    public function getSort();

    public function setSort($sort);

    public function getLimit();

    public function setLimit($limit);
}