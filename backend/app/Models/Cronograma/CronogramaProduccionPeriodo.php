<?php

namespace App\Models\Cronograma;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Validation\Rule;

class CronogramaProduccionPeriodo extends Model
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
        'nro_modulo',
        'nombre_produccion',
        'periodo',
        'ano',
        'total_desgloce',
        'ley_diluida',
        'ley_mineral',
        'ley_esteril',
        'densidad_dilucion',
        'densidad_mineral',
        'densidad_esteril',
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
        'nro_modulo' => 'integer',
        'periodo' => 'integer',
        'ano' => 'integer',
        'total_desgloce' => 'float',
        'densidad_esteril' => 'float',
        'ley_diluida' => 'float',
        'ley_mineral' => 'float',
        'ley_esteril' => 'float',
        'densidad_dilucion' => 'float',
        'densidad_mineral' => 'float',
    ];

    /**
     * The validation rules
     * @var array
     */
    public static $rules = [
        'id_datos_mina' => 'required|exists:App\Models\DatosMina\DatosMina,id',
        'nro_modulo' => 'nullable',
        'nombre_produccion' => 'nullable',
        'periodo' => 'nullable',
        'ano' => 'nullable',
        'total_desgloce' => 'nullable',
        'densidad_esteril' => 'nullable',
        'ley_diluida' => 'nullable',
        'ley_mineral' => 'nullable',
        'ley_esteril' => 'nullable',
        'densidad_dilucion' => 'nullable',
        'densidad_mineral' => 'nullable',
    ];

    /**
     * The validation filters
     * 
     * @var array
     */
    public static $filters = [
        'id_datos_mina' => 'decode_slug:App\Models\DatosMina\DatosMina',
        'nro_modulo' => 'digit|cast:integer',
        'nombre_produccion' => 'trim|escape|uppercase',
        'periodo' => 'digit|cast:integer',
        'ano' => 'digit|cast:integer',
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
     * the ValorPreparacionPeriodo related to the model
     * @return HasMany
     */
    public function valores(){
        return $this->hasMany(\App\Models\Cronograma\ValorProduccionPeriodo::class, 'id_produccion', 'id');
    }
}
