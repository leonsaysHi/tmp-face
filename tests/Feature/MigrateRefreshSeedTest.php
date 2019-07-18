<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MigrateRefreshSeedTest extends TestCase
{
    /**
     * @covers \App\Console\Commands\MigrateRefreshSeed::__construct
     * @covers \App\Console\Commands\MigrateRefreshSeed::handle
     */
    public function testMigrateRefreshSeed()
    {
        $resultAsText = Artisan::call('db:refresh');
        $this->assertEquals($resultAsText, 0);
    }
}
