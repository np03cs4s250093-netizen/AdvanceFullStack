<?php

namespace Tests\Feature;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_courses(): void
    {
        $response = $this->get('/courses');
        $response->assertStatus(200);
    }

    public function test_can_view_create_course_form(): void
    {
        $response = $this->get('/courses/create');
        $response->assertStatus(200);
    }

    public function test_can_create_course(): void
    {
        $data = [
            'name' => 'Laravel Development',
            'description' => 'Learn Laravel from scratch',
            'duration' => 8,
            'fee' => 199.99,
            'difficulty' => 'Medium',
            'is_active' => '1',
        ];

        $response = $this->post('/courses', $data);
        $response->assertRedirect('/courses');
        $this->assertDatabaseHas('courses', ['name' => 'Laravel Development']);
    }

    public function test_can_view_course_details(): void
    {
        $course = Course::create([
            'name' => 'PHP Basics',
            'duration' => 4,
            'fee' => 99.99,
            'difficulty' => 'Easy',
            'is_active' => true,
        ]);

        $response = $this->get('/courses/' . $course->id);
        $response->assertStatus(200);
        $response->assertSee('PHP Basics');
    }

    public function test_can_update_course(): void
    {
        $course = Course::create([
            'name' => 'React Basics',
            'duration' => 6,
            'fee' => 149.99,
            'difficulty' => 'Medium',
            'is_active' => true,
        ]);

        $updateData = [
            'name' => 'React & Next.js Advanced',
            'duration' => 10,
            'fee' => 299.99,
            'difficulty' => 'Hard',
            'is_active' => '1',
        ];

        $response = $this->put('/courses/' . $course->id, $updateData);
        $response->assertRedirect('/courses/' . $course->id);
        $this->assertDatabaseHas('courses', ['name' => 'React & Next.js Advanced']);
    }

    public function test_can_delete_course(): void
    {
        $course = Course::create([
            'name' => 'Old Course',
            'duration' => 2,
            'fee' => 49.99,
            'difficulty' => 'Easy',
            'is_active' => false,
        ]);

        $response = $this->delete('/courses/' . $course->id);
        $response->assertRedirect('/courses');
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }
}
