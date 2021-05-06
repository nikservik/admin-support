<?php

namespace VendorName\Skeleton\Tests\Feature;

use VendorName\Skeleton\Tests\TestCase;

class SkeletonControllerTest extends TestCase
{
    public function test_index_route_title()
    {
        $this
            ->get('package_prefix')
            ->assertOk()
            ->assertSee(':package_description');
    }
}
