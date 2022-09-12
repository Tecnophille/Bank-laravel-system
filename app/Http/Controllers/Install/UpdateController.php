<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Artisan;

class UpdateController extends Controller {
    public function __construct() {

    }

    public function update_migration() {
        Artisan::call('migrate', ['--force' => true]);
        echo "Migration Updated successfully";
    }
}
