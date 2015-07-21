<?php namespace App\Models\Scopes;

trait GridSortingTrait
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootGridSortingTrait()
    {
        //static::addGlobalScope(new MandanteScope);
    }

    /**
     * Sort Models
     *
     * @param $params
     */
    public function sorting(&$params, $defaultColumn='id', $defaultDirection=false)
    {
        if (!isset($params['direction'])) $params['direction'] = $defaultDirection;
        if (!isset($params['sortBy'])) $params['sortBy'] = $defaultColumn;

        return $this->orderBy($params['sortBy'], ($params['direction'] ? 'asc' : 'desc'));
    }
}
