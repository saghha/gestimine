<?php

namespace App\Models\Operacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Validation\Rule;

class TareasCarguioInfraestructuraPeriodo extends Model
{
    use HasHashSlug;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_carguio',
        'periodo',
        'ano',
        'turno',
        'termino',
        'orden',
        'nombre_tarea',
        'porcentaje_avance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id_carguio',
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
        'id_carguio' => 'integer',
        'periodo' => 'integer',
        'ano' => 'integer',
        'turno' => 'integer',
        'termino' => 'boolean',
        'orden' => 'integer',
        'porcentaje_avance' => 'float'
    ];

    /**
     * The validation rules
     * @var array
     */
    public static $rules = [
        'id_carguio'  => 'required|exists:App\Models\Operacion\CarguioInfraestructuraPeriodo,id',
        'periodo' => 'nullable',
        'ano' => 'nullable',
        'turno' => 'nullable',
        'termino' => 'nullable',
        'orden' => 'nullable',
        'nombre_tarea' => 'nullable',
        'porcentaje_avance' => 'nullable'
    ];

    /**
     * The validation filters
     * 
     * @var array
     */
    public static $filters = [
        'id_carguio' => 'decode_slug:App\Models\Operacion\CarguioInfraestructuraPeriodo',
        'periodo' => 'digit|cast:integer',
        'ano' => 'digit|cast:integer',
        'turno' => 'digit|cast:integer',
        'orden' => 'digit|cast:integer',
        'nombre_tarea'  => 'trim|escape|uppercase',
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
     * the CarguioInfraestructura related to the model
     * @return BelongsTo
     */
    public function carguio(){
        return $this->belongsTo(\App\Models\Operacion\CarguioInfraestructuraPeriodo::class, 'id_carguio', 'id');
    }
}
