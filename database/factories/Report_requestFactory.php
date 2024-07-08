<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Report_request;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report_request>
 */
class Report_requestFactory extends Factory
{
    protected $model = Report_request::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function withParams(int $param_user_id): Report_requestFactory
    {
        return $this->state(function (array $attributes) use ($param_user_id) {
            return [
                'reported' => $param_user_id,
                'reporter' => 2,
                'reason'   => $this->faker->randomElement(['Abuso verbal: Acoso, lenguaje ofensivo, amenazas o mensajes negativos.'    ,
                                                           'Inactividad: Cuenta en desuso'                                             ,
                                                           'Apología del odio: discriminación por la identidad, minusvalía, raza, etc.',
                                                           'Nombre ofensivo o inapropiado: Apología del odio, obscenidades u otro tipo de lenguaje ofensivo',
                                                           'Cuenta falsa: Se hace pasar por otra persona o no es quien dice ser']) ,
                'seen'     => 0
            ];
        });
    }
}
