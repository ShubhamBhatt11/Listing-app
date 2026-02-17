<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicListingTest extends TestCase
{
	/** @test */
	public function listings_page_loads()
	{
		$this->get('/listings')->assertStatus(200);
	}
}
