<?php

namespace Database\Seeders;

use App\Resources\Cons;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UploadedImage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            //
        ];
        
        DB::table('uploaded_images')->insert($data);
    }
}
