<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DistancesRepository;
use App\Entities\Distances;
use App\Validators\DistancesValidator;

/**
 * Class DistancesRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class DistancesRepositoryEloquent extends BaseRepository implements DistancesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Distances::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return DistancesValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
