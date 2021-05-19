<?php

namespace App\Models\Cronograma;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Validation\Rule;
use App\Casts\DecimalCast;

class CronogramaInfraestructuraPeriodo extends Model
{
    use HasHashSlug;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_datos_mina',
        'nombre_infraestructura',
        'seccion',
        'area',
        'longitud',
        'periodo',
        'ano',
        'total_desgloce',
        'densidad_esteril',
        'ley_diluida',
        'ley_mineral',
        'ley_esteril',
        'densidad_dilucion',
        'densidad_mineral',
        'nro_tiros'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id_datos_mina'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id_datos_mina' => 'integer',
        'area' => DecimalCast::class,
        'longitud' => DecimalCast::class,
        'periodo' => 'integer',
        'ano' => 'integer',
        'total_desgloce' => DecimalCast::class,
        'densidad_esteril' => DecimalCast::class,
        'ley_diluida' => DecimalCast::class,
        'ley_mineral' => DecimalCast::class,
        'ley_esteril' => DecimalCast::class,
        'densidad_dilucion' => DecimalCast::class,
        'densidad_mineral' => DecimalCast::class,
        'nro_tiros' => 'integer',
    ];

    /**
     * The validation rules
     * @var array
     */
    public static $rules = [
        'id_datos_mina' => 'required|exists:App\Models\DatosMina\DatosMina,id',
        'nombre_infraestructura' => 'nullable',
        'seccion' => 'nullable',
        'area' => 'nullable',
        'longitud' => 'nullable',
        'periodo' => 'nullable',
        'ano' => 'nullable',
        'total_desgloce' => 'nullable',
        'densidad_esteril' => 'nullable',
        'ley_diluida' => 'nullable',
        'ley_mineral' => 'nullable',
        'ley_esteril' => 'nullable',
        'densidad_dilucion' => 'nullable',
        'densidad_mineral' => 'nullable',
        'nro_tiros' => 'nullable'
    ];

    /**
     * The validation filters
     * 
     * @var array
     */
    public static $filters = [
        //'id_datos_mina' => 'decode_slug:App\Models\DatosMina\DatosMina',
        'nombre_infraestructura' => 'trim|escape|uppercase',
        'seccion' => 'trim|escape|uppercase',
        'periodo' => 'digit|cast:integer',
        'ano' => 'digit|cast:integer',
        'nro_tiros' => 'digit|cast:integer',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [];

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

    //DEPENDENCIAS DEL MODELO//

    /**
     * the ValorInfraestructuraPeriodo related to the model
     * @return HasMany
     */
    public function valores(){
        return $this->hasMany(\App\Models\Cronograma\ValorInfraestructuraPeriodo::class, 'id_infraestructura', 'id');
    }

    /**
     * the ValorInfraestructuraPeriodo related to the model
     * @return HasMany
     */
    public function perforaciones(){
        return $this->hasMany(\App\Models\Operacion\PerforacionInfraestructuraPeriodo::class, 'id_infraestructura', 'id');
    }

    
}
