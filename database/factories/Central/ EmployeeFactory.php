<?php

namespace Database\Factories\Central;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EmployeeFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rut' => $this->generateRut(), 
            'name' => fake()->name(),  
            'apellido_paterno'=> fake()->lastName(), 
            'apellido_materno'=> fake()->lastName(),                    
            
        ];
    }

    // Genera un RUN/RUT concatenado (EJ: 123456789)
 
    private function generateRut(): string
    {
        $number = fake()->numberBetween(1000000, 25000000); // rango real RUT
        $dv = $this->computeDv($number);

        return $number . $dv; // sin puntos ni guion
    }

    /**
     * Calcula el dígito verificador
     */
    private function computeDv(int $number): string
    {
        $s = 1;
        $m = 0;

        for (; $number; $number = intdiv($number, 10)) {
            $s = ($s + ($number % 10) * (9 - $m++ % 6)) % 11;
        }

        return $s ? chr($s + 47) : 'K';
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
