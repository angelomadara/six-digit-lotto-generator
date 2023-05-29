<?php

namespace App\Repository;

abstract class RepositoryAbstract
{
    abstract public function create();
    abstract public function update($model, array $post);
}
