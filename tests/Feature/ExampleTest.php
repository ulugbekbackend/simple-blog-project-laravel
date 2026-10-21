<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    // Bosh sahifa blogga yo'naltiradi
    public function test_home_redirects_to_posts(): void
    {
        $this->get('/')->assertRedirect('/posts');
    }
}
