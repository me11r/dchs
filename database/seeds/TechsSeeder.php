<?php

use Illuminate\Database\Seeder;

class TechsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $items = [
      // [
      //   "points"  => [
          
      //   ],
      //   "title" => "",
      //   "fill_color" => "",
      //   "line_color" => "",
      //   "opacity" => 0.0
      // ],
      
    ];

    foreach ($items as $item) {
      \App\Tech::firstOrCreate($item);
    }
  }
}
