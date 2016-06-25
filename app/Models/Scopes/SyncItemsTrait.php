<?php namespace App\Models\Scopes;

trait SyncItemsTrait
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootSyncItemsTrait()
    {
        //static::addGlobalScope(new MandanteScope);
    }

    /**
     * SyncItems
     *
     * @param $attributes
     */
    public function syncItems($attributes)
    {
        //Adicionando Grupos
        if (method_exists($this,'groups'))
            if (empty($attributes['grupos']))
                $this->groups()->sync([]);
            else
                $this->groups()->sync($attributes['grupos']);

        //Adicionando Status
        if (method_exists($this,'status'))
            if (empty($attributes['status']))
                $this->status()->sync([]);
            else
                $this->status()->sync($attributes['status']);
    }
}
