<?php

namespace App\Models\DatosMina;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Validation\Rule;
use App\Casts\DecimalCast;

class DatosMina extends Model
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
        'periodo_por_ano',
        'ano',
        'periodo',
        'meses_por_periodo',
        'dias_por_mes',
        'turnos_por_dia',
        'fecha_inicio',
        'avance_tronadura',
        'toneladas_incorporadas_tronadura',
        'ritmo_extraccion',
        'mineral_recuperado_modulo',
        'mineral_recuperado_pilares',
        'densidad_esteril',
        'densidad_mineral',
        'densidad_dilusion',
        'ley_esteril',
        'ley_mineral',
        'ley_diluida'
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
        'fecha_inicio',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        //'id_usuario' => 'integer',
        'periodo_por_ano' => 'integer',
        'ano' => 'integer',
        'periodo' => 'integer',
        'meses_por_periodo' => 'integer',
        'dias_por_mes' => 'integer',
        'turnos_por_dia' => 'integer',
        'avance_tronadura' => DecimalCast::class,
        'toneladas_incorporadas_tronadura' => DecimalCast::class,
        'ritmo_extraccion' => DecimalCast::class,
        'mineral_recuperado_modulo' => DecimalCast::class,
        'mineral_recuperado_pilares' => DecimalCast::class,
        'densidad_esteril' => DecimalCast::class,
        'densidad_mineral' => DecimalCast::class,
        'densidad_dilusion' => DecimalCast::class,
        'ley_esteril' => DecimalCast::class,
        'ley_mineral' => DecimalCast::class,
        'ley_diluida' => DecimalCast::class
    ];

    /**
     * The validation rules
     * @var array
     */
    public static $rules = [
        //'id_usuario' => 'required|exists:App\Models\User,id',
        'periodo_por_ano' => 'required',
        'ano' => 'required',
        'periodo' => 'required',
        'meses_por_periodo' => 'nullable',
        'dias_por_mes' => 'nullable',
        'turnos_por_dia' => 'nullable',
        'fecha_inicio' => 'nullable',
        'avance_tronadura' => 'nullable',
        'toneladas_incorporadas_tronadura' => 'nullable',
        'ritmo_extraccion' => 'nullable',
        'mineral_recuperado_modulo' => 'nullable',
        'mineral_recuperado_pilares' => 'nullable',
        'densidad_esteril' => 'nullable',
        'densidad_mineral' => 'nullable',
        'densidad_dilusion' => 'nullable',
        'ley_esteril' => 'nullable',
        'ley_mineral' => 'nullable',
        'ley_diluida' => 'nullable'
    ];

    /**
     * The validation filters
     * 
     * @var array
     */
    public static $filters = [
        //'id_usuario' => 'decode_slug:App\Models\User',
        'periodo_por_ano' => 'digit|cast:integer',
        'ano' => 'digit|cast:integer',
        'periodo' => 'digit|cast:integer',
        'meses_por_periodo' => 'digit|cast:integer',
        'dias_por_mes' => 'digit|cast:integer',
        'turnos_por_dia' => 'digit|cast:integer',
        'fecha_inicio' => 'trim|format_date:d/m/Y, Y-m-d',
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
}
