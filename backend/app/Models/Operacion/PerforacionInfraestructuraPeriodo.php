<?php

namespace App\Models\Operacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Validation\Rule;
use App\Casts\DecimalCast;

class PerforacionInfraestructuraPeriodo extends Model
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
        'registro_desgloce',
        'valor_perforacion',
        'total_perforacion'
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
        'registro_desgloce' => DecimalCast::class,
        'valor_perforacion' => DecimalCast::class,
        'total_perforacion' => DecimalCast::class
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
        'registro_desgloce' => 'nullable',
        'valor_perforacion' => 'nullable',
        'total_perforacion' => 'nullable'
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
        return $this->hasMany(\App\Models\Operacion\TareasPerforacionInfraestructuraPeriodo::class, 'id_perforacion', 'id');
    }

    /**
     * the CronogramaInfraestructura related to the model
     * @return BelongsTo
     */
    public function infraestructura(){
        return $this->belongsTo(\App\Models\Cronograma\CronogramaInfraestructuraPeriodo::class, 'id_infraestructura', 'id');
    }

}
