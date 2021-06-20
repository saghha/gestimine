<?php

namespace App\Models\RegistroDatos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Validation\Rule;

class EventoPeriodo extends Model
{
    use HasHashSlug;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_usuario',
        'operacion_infraestructura',
        'periodo',
        'ano',
        'fecha',
        'evento',
        'tipo',
        'resultado',
        'mensaje',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id_usuario',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'fecha',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id_usuario' => 'integer',
        'periodo' => 'integer',
        'ano' => 'integer',
    ];

    /**
     * The validation rules
     * @var array
     */
    public static $rules = [
        //'id_usuario' => 'required|exists:App\Models\User,id',
        'operacion_infraestructura' => 'nullable',
        'periodo' => 'required',
        'ano' => 'required',
        'fecha' => 'nullable',
        'evento' => 'nullable',
        'tipo' => 'nullable',
        'resultado' => 'nullable',
        'mensaje' => 'required',
    ];

    /**
     * The validation filters
     * 
     * @var array
     */
    public static $filters = [
        //'id_usuario' => 'decode_slug:App\Models\User',
        'operacion_infraestructura' => 'trim|escape',
        'periodo' => 'digit|cast:integer',
        'ano' => 'digit|cast:integer',
        'fecha' => 'trim|format_date:d/m/Y, Y-m-d',
        'evento' => 'trim|escape|uppercase',
        'tipo' => 'trim|escape|uppercase',
        'resultado' => 'trim|escape|uppercase',
        'mensaje' => 'trim|escape',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [

    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['slug'];

    /**
	 * the Slug attribute
	 * @return string
	 * 
	 */
	public function getSlugAttribute(){
        return $this->slug();
    }

    /**
     * the User related to the model
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(\App\Models\User::class, 'id_usuario', 'id');
    }
}
