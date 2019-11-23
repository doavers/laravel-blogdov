<?php

use Illuminate\Database\Seeder;
use App\Tag;
use App\Post;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->truncate();

        $php = new Tag();
        $php->name = "PHP";
        $php->slug = "php";
        $php->save();
        
        $laravel = new Tag();
        $laravel->name = "Laravel";
        $laravel->slug = "laravel";
        $laravel->save();
        
        $python = new Tag();
        $python->name = "Python";
        $python->slug = "python";
        $python->save();
        
        $android = new Tag();
        $android->name = "Android";
        $android->slug = "android";
        $android->save();

        $tags = [
            $php->id,
            $laravel->id,
            $python->id,
            $android->id
        ];

        foreach(Post::all() as $post) {
            shuffle($tags);
            for($i = 0; $i < rand(0, count($tags)-1); $i++) {
                $post->tags()->detach($tags[$i]);
                $post->tags()->attach($tags[$i]);
            }
        }
    }
}
