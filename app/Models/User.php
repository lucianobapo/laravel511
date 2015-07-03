<?php namespace App\Models;

use App\Repositories\MessagesRepository;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword, SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
//	protected $fillable = ['mandante', 'avatar', 'name', 'email', 'password'];
    protected $fillable = [
        'mandante',
        'name',
        'email', 'password', 'username', 'avatar','provider_id', 'provider', 'role_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];


    /**
     * User can have many address.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses(){
        return $this->hasMany('App\Models\Address');
    }

    /**
     * User can have many articles.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles(){
        return $this->hasMany('App\Models\Article');
    }

    /**
     * User can have one Partner.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function partner(){
        return $this->hasOne('App\Models\Partner');
    }

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return static
     */
    public static function create(array $attributes = [], $criaPartner=true, $enviaMensagem=true)
    {
        if (!isset($attributes['mandante'])) $attributes['mandante'] = config('delivery.defaultMandante');

        $model = new static($attributes);
        $model->save();

        if ($criaPartner) static::createPartner($model);

        return $model;
    }

    private static function createPartner($user){
        $addedPartner = (new Partner)->firstOrCreate([
            'mandante' => $user->mandante,
            'user_id' => $user->id,
            'nome' => $user->name,
        ]);

        $addedContact = (new Contact)->firstOrCreate([
            'mandante' => $user->mandante,
            'partner_id' => $addedPartner->id,
            'contact_type' => 'email',
            'contact_data' => $user->email,
        ]);

        $addedPartner->status()->sync([0=>SharedStat::where(['status'=>'ativado'])->first()->id]);
        $addedPartner->groups()->sync([0=>PartnerGroup::where(['grupo'=>'Cliente'])->first()->id]);

//        \Debugbar::info($addedContact);

//        $addedPartner->user()->save($addedPartner);
//        $user->partner()->save($addedPartner);
//        $addedPartner->contacts()->save( (new Contact)->getAddedContact('email', $user->email));
//        $addedPartner->contacts()->save( );
    }


    private $have_role;

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'role_id');
    }

    public function hasRole($roles)
    {
        $this->have_role = $this->getUserRole();
//        dd($this->have_role);

        // Check if the user is a root account
        if ( (!is_null($this->have_role))&&($this->have_role->name == 'Root') ) {
            return true;
        }

        if(is_array($roles)){
            foreach($roles as $need_role){
                if($this->checkIfUserHasRole($need_role)) {
                    return true;
                }
            }
        } else{
            return $this->checkIfUserHasRole($roles);
        }
        return false;
    }

    private function getUserRole()
    {
        return $this->role()->getResults();
    }

    private function checkIfUserHasRole($need_role)
    {
        return is_null($this->have_role)?false:(strtolower($need_role)==strtolower($this->have_role->name)) ? true : false;
    }

}
