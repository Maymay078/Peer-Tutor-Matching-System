<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $realisticMajors = [
            'Doctor of Medicine',
            'Information Systems',
            'Human Resource Management',
            'Psychology',
            'Business Administration',
            'Computer Science',
            'Mechanical Engineering',
            'Electrical Engineering',
            'Civil Engineering',
            'Nursing',
            'Pharmacy',
            'Law',
            'Economics',
            'Accounting',
            'Marketing',
            'Environmental Science',
            'Architecture',
            'Sociology',
            'Political Science',
            'Education',
        ];

        $faculties = \App\Models\SubjectNormalizer::getFaculties();
        $randomFaculty = $this->faker->randomElement($faculties);
        $subjects = \App\Models\SubjectNormalizer::getSuggestions($randomFaculty);

        return [
            'student_id' => $this->faker->unique()->numerify('S######'),
            'major' => $this->faker->randomElement($realisticMajors),
            'year' => $this->faker->numberBetween(1, 5),
            'preferred_course' => json_encode($this->faker->randomElements($subjects, min(2, count($subjects)))),
        ];
    }
}
