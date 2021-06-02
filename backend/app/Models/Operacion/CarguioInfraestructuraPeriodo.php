<?php

namespace App\Models\Operacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Validation\Rule;
use App\Casts\DecimalCast;

class CarguioInfraestructuraPeriodo extends Model
{
    use HasHashSlug;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_infraestructura',
        'periodo',
        'ano',
        'termino',
        'registro_desgloce_carguio',
        'valor_carguio',
        'total_carguio',
        'registro_desgloce_total',
        'valor_total',
        'total_total'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id_infraestructura',
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
        'id_infraestructura' => 'integer',
        'periodo' => 'integer',
        'ano' => 'integer',
        'termino' => 'boolean',
        'registro_desgloce_carguio' => DecimalCast::class,
        'valor_carguio' => DecimalCast::class,
        'total_carguio' => DecimalCast::class,
        'registro_desgloce_total' => DecimalCast::class,
        'valor_total' => DecimalCast::class,
        'total_total' => DecimalCast::class
    ];

    /**
     * The validation rules
     * @var array
     */
    public static $rules = [
        'id_infraestructura'  => 'required|exists:App\Models\Cronograma\CronogramaInfraestructuraPeriodo,id',
        'periodo' => 'nullable',
        'ano' => 'nullable',
        'termino' => 'nullable',
        'registro_desgloce_carguio' => 'nullable',
        'valor_carguio' => 'nullable',
        'total_carguio' => 'nullable',
        'registro_desgloce_total' => 'nullable',
        'valor_total' => 'nullable',
        'total_total' => 'nullable'
    ];

    /**
     * The validation filters
     * 
     * @var array
     */
    public static $filters = [
        //'id_infraestructura' => 'decode_slug:App\Models\Cronograma\CronogramaInfraestructuraPeriodo',
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
     * the TareasPeriodoInfraestructuraPeriodo related to the model
     * @return HasMany
     */
    public function tareas(){
        return $this->hasMany(\App\Models\Operacion\TareasCarguioInfraestructuraPeriodo::class, 'id_carguio', 'id');
    }

    /**
     * the CronogramaInfraestructura related to the model
     * @return BelongsTo
     */
    public function infraestructura(){
        return $this->belongsTo(\App\Models\Cronograma\CronogramaInfraestructuraPeriodo::class, 'id_infraestructura', 'id');
    }

}
