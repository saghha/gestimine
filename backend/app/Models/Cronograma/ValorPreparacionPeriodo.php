<?php

namespace App\Models\Cronograma;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Balping\HashSlug\HasHashSlug;
use Illuminate\Validation\Rule;
use App\Casts\DecimalCast;

class ValorPreparacionPeriodo extends Model
{
    use HasHashSlug;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_preparacion',
        'periodo',
        'ano',
        'valor_desgloce',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id_preparacion'
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
        'id_preparacion' => 'integer',
        'periodo' => 'integer',
        'ano' => 'integer',
        'valor_desgloce'  => DecimalCast::class,
    ];

    /**
     * The validation rules
     * @var array
     */
    public static $rules = [
        'id_preparacion'  => 'required|exists:App\Models\Cronograma\CronogramaPreparacionPeriodo,id',
        'periodo' => 'nullable',
        'ano' => 'nullable',
        'valor_desgloce' => 'nullable',
    ];

    /**
     * The validation filters
     * 
     * @var array
     */
    public static $filters = [
        //'id_preparacion' => 'decode_slug:App\Models\Cronograma\CronogramaPreparacionPeriodo',
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
