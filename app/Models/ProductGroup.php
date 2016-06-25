<?php namespace App\Models;

use App\Models\Scopes\GridSortingTrait;
use App\Models\Scopes\MandanteTrait;
use App\Models\Scopes\SyncItemsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductGroup extends Model {

    use SoftDeletes;
    use MandanteTrait;
    use GridSortingTrait;
    use SyncItemsTrait;

    /**
     * Fillable fields for a ProductGroup.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'grupo',
    ];

    /**
     * Get the products associated with the given ProductGroup.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products() {
        return $this->belongsToMany('App\Models\Product')->withTimestamps();
    }

    /**
     * Get the status associated with the given Partner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function status() {
        return $this->belongsToMany('App\Models\SharedStat')->withTimestamps();
    }

    public function getStatusListAttribute(){
        $status = $this->status->toArray();
        $lista = '';
        foreach($status as $stat){
            $lista = $lista . $stat['descricao'].', ';
        }
        return substr($lista, 0, -2);
    }

    public function checkStatus(array $lista, $status){
        foreach($lista as $item) if ($item['status']==$status) return true;
        return false;
    }

}
