<?php namespace App\Models\Scopes;

use Illuminate\Database\Query\Builder as BaseBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;
use Illuminate\Support\Facades\Auth;

class MandanteScope implements ScopeInterface
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (isset(Auth::user()->mandante))
            $builder->where($model->getQualifiedMandanteColumn(), '=', Auth::user()->mandante);
//        $builder->where($model->getQualifiedMandanteColumn(), 'LIKE', 'ilhanett');
//        $builder->whereNotNull($model->getQualifiedMandanteColumn());

//        $this->extend($builder);
//        $this->addWithMandante($builder);
    }

    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
//    public function remove(Builder $builder, Model $model)
//    {
//        $column = $model->getQualifiedMandanteColumn();
//
//        $query = $builder->getQuery();
//
//        $query->wheres = collect($query->wheres)->reject(function ($where) use ($column) {
//            return $this->isMandanteConstraint($where, $column);
//        })->values()->all();
//    }

    /**
     * Remove scope from the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function remove(Builder $builder, Model $model)
    {
        $query = $builder->getQuery();

        $column = $model->getQualifiedMandanteColumn();

        $bindingKey = 0;

        foreach ((array) $query->wheres as $key => $where)
        {
            if ($this->isMandanteConstraint($where, $column))
            {
                $this->removeWhere($query, $key);

                // Here SoftDeletingScope simply removes the where
                // but since we use Basic where (not Null type)
                // we need to get rid of the binding as well
                $this->removeBinding($query, $bindingKey);
            }

            // Check if where is either NULL or NOT NULL type,
            // if that's the case, don't increment the key
            // since there is no binding for these types
            if ( ! in_array($where['type'], ['Null', 'NotNull'])) $bindingKey++;
        }
    }

    /**
     * Remove scope constraint from the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $builder
     * @param  int  $key
     * @return void
     */
    protected function removeWhere(BaseBuilder $query, $key)
    {
        unset($query->wheres[$key]);

        $query->wheres = array_values($query->wheres);
    }

    /**
     * Remove scope constraint from the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $builder
     * @param  int  $key
     * @return void
     */
    protected function removeBinding(BaseBuilder $query, $key)
    {
        $bindings = $query->getRawBindings()['where'];

        unset($bindings[$key]);

        $query->setBindings($bindings);
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
//    public function extend(Builder $builder)
//    {
//        foreach ($this->extensions as $extension) {
//            $this->{"add{$extension}"}($builder);
//        }
//
//        $builder->onDelete(function (Builder $builder) {
//            $column = $this->getDeletedAtColumn($builder);
//
//            return $builder->update([
//                $column => $builder->getModel()->freshTimestampString(),
//            ]);
//        });
//    }

    /**
     * Extend Builder with custom method.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     */
    protected function addWithMandante(Builder $builder)
    {
        $builder->macro('withMandante', function(Builder $builder)
        {
            $this->remove($builder, $builder->getModel());

            return $builder;
        });
    }

    /**
     * Get the "deleted at" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return string
     */
//    protected function getDeletedAtColumn(Builder $builder)
//    {
//        if (count($builder->getQuery()->joins) > 0) {
//            return $builder->getModel()->getQualifiedDeletedAtColumn();
//        } else {
//            return $builder->getModel()->getDeletedAtColumn();
//        }
//    }

    /**
     * Determine if the given where clause is a soft delete constraint.
     *
     * @param  array   $where
     * @param  string  $column
     * @return bool
     */
//    protected function isSoftDeleteConstraint(array $where, $column)
//    {
//        return $where['type'] == 'Null' && $where['column'] == $column;
//    }

    /**
     * Check if given where is the scope constraint.
     *
     * @param  array   $where
     * @param  string  $column
     * @return boolean
     */
    protected function isMandanteConstraint(array $where, $column)
    {
        return ($where['type'] == 'Basic' && $where['column'] == $column && $where['value'] == 1);
    }
}
