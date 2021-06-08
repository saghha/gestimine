<?php

namespace App\Models\Cronograma;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Validation\Rule;
use App\Casts\DecimalCast;

class ValorProduccionPeriodo extends Model
{
    use HasHashSlug;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_produccion',
        'periodo',
        'ano',
        'valor_desgloce',
        'valor_desgloce_perforacion',
        'valor_desgloce_carguio',
        'valor_desgloce_tronadura',
        'valor_desgloce_transporte',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id_produccion'
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
        'id_produccion' => 'integer',
        'periodo' => 'integer',
        'ano' => 'integer',
        'valor_desgloce' => DecimalCast::class,
        'valor_desgloce_perforacion' => DecimalCast::class,
        'valor_desgloce_carguio' => DecimalCast::class,
        'valor_desgloce_tronadura' => DecimalCast::class,
        'valor_desgloce_transporte' => DecimalCast::class,
    ];

    /**
     * The validation rules
     * @var array
     */
    public static $rules = [
        'id_produccion'  => 'required|exists:App\Models\Cronograma\CronogramaProduccionPeriodo,id',
        'periodo' => 'nullable',
        'ano' => 'nullable',
        'valor_desgloce' => 'nullable',
        'valor_desgloce_perforacion' => 'nullable',
        'valor_desgloce_carguio' => 'nullable',
        'valor_desgloce_tronadura' => 'nullable',
        'valor_desgloce_transporte' => 'nullable',
    ];

    /**
     * The validation filters
     * 
     * @var array
     */
    public static $filters = [
        'id_produccion' => 'decode_slug:App\Models\Cronograma\CronogramaProduccionPeriodo',
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
}
