<?php

namespace App\Repositories;

interface RepositoryInterface {

    public function all();

    public function create(array $data);

    public function update(array $data, string $slug);

    public function delete(string $slug);

    public function find(string $slug);
}