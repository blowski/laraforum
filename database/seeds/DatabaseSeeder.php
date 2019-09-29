<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $users = [];
        $users['bob'] = create(\App\User::class, [
            'name' => 'Bob Biscuits',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
        ]);
        $users['charlie'] = create(\App\User::class, [
            'name' => 'Charlie Chalk',
            'email' => 'charlie@example.com',
            'password' => bcrypt('password'),
        ]);
        for ($ii = 0; $ii < 30; $ii++) {
            $createdAt = $faker->dateTimeBetween('-2 years', 'now');
            $users[] = create(\App\User::class, [
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt($faker->password(8)),
                'created_at' => $createdAt,
                'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
            ]);
        }

        $channels = [];
        for ($ii = 0; $ii < 20; $ii++) {
            $name = $faker->words(random_int(1, 3), true);
            $slug = \Illuminate\Support\Str::slug($name);
            $channels[] = create(\App\Channel::class, [
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        $threads = [];
        for ($ii = 0; $ii < 100; $ii++) {
            $user = $faker->randomElement($users);
            $createdAt = $faker->dateTimeBetween($user->created_at, 'now');
            $threads[] = create(\App\Thread::class, [
                'user_id' => $user->id,
                'channel_id' => $faker->randomElement($channels)->id,
                'created_at' => $createdAt,
                'updated_at' => $faker->dateTimeBetween($createdAt, 'now'),
            ]);
        }

        $replies = [];
        for($ii = 0; $ii<500; $ii++) {
            $inReplyTo = $faker->randomElement($threads);
            $createdAt = $faker->dateTimeBetween($inReplyTo->created_at, 'now');
            $updatedAt = $faker->dateTimeBetween($createdAt, 'now');
            $user = $faker->randomElement($users);
            $replies[] = create(\App\Reply::class, [
                'user_id' => $user->id,
                'thread_id' => $inReplyTo->id,
                'updated_at' => $updatedAt,
                'created_at' => $createdAt,
            ]);
        }

        $favouriteReplies = [];
        for($ii = 0; $ii<1000; $ii++) {
            $reply = $faker->randomElement($replies);
            $favouritedAt = $faker->dateTimeBetween($reply->created_at, 'now');
            $user = $faker->randomElement($users);
            try {
                $favouriteReplies[] = create(\App\Favourite::class, [
                    'user_id' => $user->id,
                    'created_at' => $favouritedAt,
                    'updated_at' => $favouritedAt,
                    'favourited_id' => $reply->id,
                    'favourited_type' => \App\Reply::class,
                ]);
            } catch(\Illuminate\Database\QueryException $exception) {
                continue;
            }
        }

    }
}
