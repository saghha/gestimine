<?php

namespace App\Repositories\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;

/**
 * Este trait realiza las operaciones básicas de un crud dado un modelo
 */
trait BasicCrudOperations
{
    protected $model;

    /**
     * get the queried model
     * useful with pagination
     * @return \Illuminate\Database\Query\Builder
     */
    public function queryAll(){
        return $this->model;
    }

    /**
     * get the raw query
     * useful with pagination
     * @return \Illuminate\Database\Query\Builder
     */
    public function query(){
        return $this->model->query();
    }

    /**
     * get all model
     * @return \Illuminate\Support\Collection
     */
    public function all(){
        return $this->model->get();
    }

    /**
     * get a single model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(string $slug){
        $model = $this->model->findBySlug($slug);
        if ($model) return $model;
        else throw new ModelNotFoundException(class_basename($this->model)." not found");
    }

    /**
     * update a single model
     * @param array $data
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function edit(array $data, string $slug){
        $id = $this->model->decodeSlug($slug);
        if ($id === null) throw new ModelNotFoundException(class_basename($this->model)." not found");
        $this->beforeUpdate($data, $id);
        return $this->model->where('id', $id)->update(Arr::only($data, $this->attributes()));
    }

    /**
     * create a single model
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model 
     */
    public function create(array $data){
        $prepared_data = $data;
        $this->beforeCreate($prepared_data);
        return $this->model->create($prepared_data);
    }

    /**
     * deletes a single model
     * @param string $slug
     */
    public function delete(string $slug){
        $model = $this->model->findBySlug($slug);
        if ($model) return $model->delete();
        else throw new ModelNotFoundException(class_basename($this->model)." not found");
    }

    /**
     * Gets the model rules
     * @return array
     */
    public function rules(){
        return $this->model->rules;
    }

    /**
     * Gets the model attributes
     * @return array
     */
    public function attributes(){
        return $this->model->getFillable();
    }

    /**
     * Throw and exception if some key is not unique
     * to be implemented by every repository
     * @param array $data
     * @return bool
     */
    public function beforeCreate($data){
        return true;
    }

    /**
     * Throw and exception if some key is not unique
     * to be implemented by every repository
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function beforeUpdate($data, $id){
        return true;
    }
}
