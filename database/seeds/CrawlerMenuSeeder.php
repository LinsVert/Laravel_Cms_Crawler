<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


/**
 * Crawler Menu 数据填充
 */
class CrawlerMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //todo
        $menu_table = config('admin.database.menu_table');
        DB::table($menu_table)->insert([
            'parent_id' => 0,
            'order' => 1,
            'title' => 'Cralwer Menu',
            'icon' => 'Menu',
            'uri' => ''
        ]);
    }
}
