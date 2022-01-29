<?php

namespace test\functional\Infrastructure\TestClasses;

interface BlaChildInterface extends BlaInterface
{
    public function test(Bla $t);
}