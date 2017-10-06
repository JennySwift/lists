<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\TransformerAbstract;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * For Fractal transformer
     * @param $resource
     * @param null $includes
     * @return array
     */
    public function transform($resource, $includes = null)
    {
        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer);

        if ($includes) {
            $manager->parseIncludes($includes);
        }

        return $manager->createData($resource)->toArray();
    }

    /**
     * For Fractal transformer
     * @param $model
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Collection
     */
    public function createCollection($model, TransformerAbstract $transformer, $key = null)
    {
        return new Collection($model, $transformer, $key);
    }

    /**
     *
     * @param $model
     * @param TransformerAbstract $transformer
     * @param null $key
     * @return Item
     */
    public function createItem($model, TransformerAbstract $transformer, $key = null)
    {
        return new Item($model, $transformer, $key);
    }
}
