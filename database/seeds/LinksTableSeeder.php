<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'link_name' => '百度',
                'link_title' => '百度一下,你就知道',
                'link_url' => 'https://www.baidu.com/',
                'link_order' => 1,
            ],
            [
                'link_name' => '淘宝',
                'link_title' => '淘宝一下,你就知道',
                'link_url' => 'https://www.taobao.com.com/',
                'link_order' => 1,
            ]
        ];
        DB::table('links')->insert($data);
    }
}
