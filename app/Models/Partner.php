<?php namespace App\Models;

use App\Models\Scopes\GridSortingTrait;
use App\Models\Scopes\SyncItemsTrait;
use Carbon\Carbon;
use DebugBar\DebugBar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;
use Illuminate\Support\Facades\Auth;

class Partner extends Model {

    use SoftDeletes;
    use MandanteTrait;
    use GridSortingTrait;
    use SyncItemsTrait;

    /**
     * Fillable fields for a Partner.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'user_id',
        'nome',
        'data_nascimento',
        'observacao',
    ];

    /**
     * Additional fields to treat as Carbon instances.
     *
     * @var array
     */
    protected $dates = ['data_nascimento'];

    /**
     * Set the data_nascimento attribute.
     *
     * @param $date
     */
    public function setDataNascimentoAttribute($date) {
        if (empty($date))
            $this->attributes['data_nascimento'] = null;
        else
            $this->attributes['data_nascimento'] = Carbon::parse($date);

    }

    /**
     * Get the data_nascimento attribute.
     *
     * @return string
     */
    public function getDataNascimentoAttribute() {
        if (empty($this->attributes['data_nascimento'])) return '';
        else return Carbon::parse($this->attributes['data_nascimento'])->format('d/m/Y');
    }

    /**
     * Get the posted_at attribute.
     * @return string
     */
    public function getDataNascimentoForFieldAttribute() {
        if (empty($this->attributes['data_nascimento'])) return '';
        else return Carbon::parse($this->attributes['data_nascimento'])->format('Y-m-d');
    }


    public function setUserIdAttribute($id) {
        if (empty($id))
            $this->attributes['user_id'] = null;
        else
            $this->attributes['user_id'] = $id;
    }

    /**
     * Get the groups associated with the given partner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups() {
        return $this->belongsToMany('App\Models\PartnerGroup')->withTimestamps();
    }

    public function getGroupListAttribute(){
        $groups = $this->groups->toArray();
        $lista = '';
        foreach($groups as $group){
            $lista = $lista . $group['grupo'].', ';
        }
        return substr($lista, 0, -2);
    }



    /**
     * A Partner belongs to a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\Models\User');
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

    /**
     * Partner can have many orders.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(){
        return $this->hasMany('App\Models\Order');
    }

    /**
     * Partner can have many addresses.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(){
        return $this->hasMany('App\Models\Address');
    }

    /**
     * Partner can have many contacts.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts(){
        return $this->hasMany('App\Models\Contact');
    }
    public function getContactListAttribute(){
        $contacts = $this->contacts->toArray();
        $lista = '';
        foreach($contacts as $contact){
            $lista = $lista . ucfirst($contact['contact_type']).', ';
        }
        return substr($lista, 0, -2);
    }

    /**
     * Partner can have many documents.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(){
        return $this->hasMany('App\Models\Document');
    }

    /**
     * Get the first model for the given attributes.
     *
     * @param  array  $attributes
     * @return static|null
     */
    protected static function firstByAttributes($attributes)
    {
        return static::where($attributes)->first();
    }

    /**
     * Get the partner_list attribute.
     * @return string
     */
//    public function getPartnerListAttribute() {
////        $partners = SharedStat::where(['status'=>'ativado'])->first()->partners()->get();
////        foreach($partners as $partner) {
//////            $list['id'] = $partner->id;
////            $list['nome'] = $partner->nome;
////        }
////        $endereco = Address::where(['partner_id'=>$this->attributes['id']])->first();
//
////        if (is_null($this->status()->where(['status'=>'ativado'])->first())) {
////            \Debugbar::info($this->attributes['nome']);
////            return $this->attributes['nome'];
////        }else return null;
//        return $this->attributes['nome'];
////        return $list;
//    }

//    public function getPartnerListAttribute(){
//        return $this->with('groups','status','addresses')
//            ->orderBy('nome', 'asc')
//            ->get()
//            ->filter(function($item) {
//                if ( (strpos($item->status_list,'Ativado')!==false) || (Auth::user()->role->name==config('delivery.rootRole')) )
//                    return $item;
//            });
//    }
//
//    public function getPartnerSelectListAttribute(){
//        return [''=>''] + $this->partner_list
//            ->lists('nome','id')
//            ->toArray();
//    }
}
