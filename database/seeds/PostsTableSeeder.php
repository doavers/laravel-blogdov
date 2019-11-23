<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Carbon\Carbon;

class PostsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// reset the post table
		DB::table('posts')->truncate();

		// generate 10 dummy posts data
		$posts = [];
		$faker = Factory::create();
		$date = Carbon::create(2018, 10, 20, 9);

		for ($i=0; $i < 30; $i++) 
		{
			$image = "Post_image_" . rand(1, 5) . ".jpg";
			// $date = date("Y-m-d H:i:s", strtotime("2018-10-31 08:00:00 +{$i} days"));
			$createdDate = clone($date);
			$createdDate = $createdDate->addDays($i);
			$publishDate = clone($date);
			$publishDate = rand(0, 1) == 0 ? NULL : $publishDate->addDays($i + rand(4, 10));

			$posts[] = [
				'author_id'		=> rand(1, 3),
				'title'			=> $faker->sentence(rand(8, 12)),
				'slug'			=> $faker->slug(),
				'excerpt'		=> $faker->text(rand(200, 300)),
				'body'			=> $faker->paragraphs(rand(10, 15), true),
				'image'			=> rand(0, 1) == 1 ? $image : NULL,
				'created_at' 	=> $createdDate,
				'updated_at' 	=> $createdDate,
				'published_at' 	=> $publishDate,
				'category_id'   => rand(1, 5),
				'view_count'    => rand(1, 10) * 10
			];
		}

		DB::table('posts')->insert($posts);
	}
}
