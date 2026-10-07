<?php

namespace Tests\Feature;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_students(): void
    {
        $response = $this->get('/students');
        $response->assertStatus(200);
    }

    public function test_can_view_create_student_form(): void
    {
        $response = $this->get('/students/create');
        $response->assertStatus(200);
    }

    public function test_can_create_student(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'date_of_birth' => '2000-01-01',
        ];

        $response = $this->post('/students', $data);
        $response->assertRedirect('/students');
        $this->assertDatabaseHas('students', ['email' => 'john@example.com']);
    }

    public function test_can_view_student_details(): void
    {
        $student = Student::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '0987654321',
        ]);

        $response = $this->get('/students/' . $student->id);
        $response->assertStatus(200);
        $response->assertSee('Jane Doe');
    }

    public function test_can_update_student(): void
    {
        $student = Student::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'phone' => '1112223333',
        ]);

        $updateData = [
            'name' => 'Bob Updated',
            'email' => 'bob@example.com',
            'phone' => '1112223333',
        ];

        $response = $this->put('/students/' . $student->id, $updateData);
        $response->assertRedirect('/students/' . $student->id);
        $this->assertDatabaseHas('students', ['name' => 'Bob Updated']);
    }

    public function test_can_delete_student(): void
    {
        $student = Student::create([
            'name' => 'Alice White',
            'email' => 'alice@example.com',
            'phone' => '4445556666',
        ]);

        $response = $this->delete('/students/' . $student->id);
        $response->assertRedirect('/students');
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
