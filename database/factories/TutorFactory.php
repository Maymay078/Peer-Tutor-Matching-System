<?php

namespace Database\Factories;

use App\Models\Tutor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutor>
 */
class TutorFactory extends Factory
{
    protected $model = Tutor::class;

    public function definition(): array
    {
        $faculties = \App\Models\SubjectNormalizer::getFaculties();
        $randomFaculty = $this->faker->randomElement($faculties);
        $subjects = \App\Models\SubjectNormalizer::getSuggestions($randomFaculty);

        return [
            'tutor_id' => $this->faker->unique()->numerify('T######'),
            'expertise' => $this->faker->randomElement($subjects),
            'payment_details' => $this->faker->randomFloat(2, 5, 20), // hourly rate between 5 and 20
            'rating' => $this->faker->randomElement([3, 4, 5]), // rating 3, 4, or 5 stars
            'availability' => json_encode([]), // Empty availability for seeding
        ];
    }
}
