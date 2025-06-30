<?php

namespace App\Models;

class SubjectNormalizer
{
    /**
     * Common subject variations and their normalized forms
     */
    private static $commonVariations = [
        'math' => 'mathematics',
        'maths' => 'mathematics',
        'bio' => 'biology',
        'chem' => 'chemistry',
        'cs' => 'computer science',
        'comp sci' => 'computer science',
        'programming' => 'computer science',
        'physics' => 'physics',
        'eng' => 'english',
        'stat' => 'statistics',
        'stats' => 'statistics',
    ];

    /**
     * Faculty categories and their common subjects
     */
    public static $faculties = [
        'Economics and Business' => [
            'Accounting',
            'Economics',
            'Finance',
            'Marketing',
            'Business Statistics',
            'Management',
        ],
        'Engineering' => [
            'Calculus',
            'Physics',
            //'Computer Programming', // Removed as per user request
            'Circuit Theory',
            'Digital Systems',
            'Mechanics',
            'Thermodynamics',
            'Control Systems',
        ],
        'Applied and Creative Arts' => [
            'Graphic Design',
            'Photography',
            'Music',
            'Theatre',
            'Visual Arts',
        ],
        'Cognitive Sciences and Human Development' => [
            'Psychology',
            'Neuroscience',
            'Linguistics',
            'Human Development',
        ],
        'Medicine and Health Sciences' => [
            'Anatomy',
            'Physiology',
            'Pharmacology',
            'Public Health',
            'Nursing',
        ],
        'Social Sciences and Humanities' => [
            'History',
            'Sociology',
            'Political Science',
            'Philosophy',
            'Anthropology',
        ],
        'Resource Science and Technology' => [
            'Environmental Science',
            'Geology',
            'Forestry',
            'Natural Resource Management',
        ],
        'Computer Science and Information Technology' => [
            'Computer Science',
            'Programming',
            'Database Systems',
            'Web Development',
            'Software Engineering',
            'Data Structures',
            'Algorithms',
        ],
        'Language and Communication' => [
            'English',
            'Linguistics',
            'Communication Studies',
            'Foreign Languages',
        ],
        'Built Environment' => [
            'Architecture',
            'Urban Planning',
            'Construction Management',
            'Landscape Architecture',
        ],
    ];

    /**
     * Normalize a subject name for consistent matching
     */
    public static function normalize($subject)
    {
        // Convert to lowercase and trim
        $subject = strtolower(trim($subject));
        
        // Remove multiple spaces
        $subject = preg_replace('/\s+/', ' ', $subject);
        
        // Check for common variations
        if (isset(self::$commonVariations[$subject])) {
            return self::$commonVariations[$subject];
        }
        
        return $subject;
    }

    /**
     * Get suggested subjects for a faculty
     */
    public static function getSuggestions($faculty)
    {
        return self::$faculties[$faculty] ?? [];
    }

    /**
     * Get all faculties
     */
    public static function getFaculties()
    {
        return array_keys(self::$faculties);
    }
}
