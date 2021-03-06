<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{ User, Channel, Thread, Reply };
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'created_at' => $faker->dateTimeInInterval('-1 year'),
    ];
});

$factory->define(Thread::class, function(Faker $faker) {
    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'channel_id' => function() {
            return factory(Channel::class)->create()->id;
        },
        'title' => ucfirst($faker->word) . $faker->words(random_int(3, 10), true) . '.',
        'body' => ucfirst($faker->word) . $faker->words(random_int(10, 50), true) . '.',
    ];
});

$factory->define(Reply::class, function(Faker $faker) {
    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'thread_id' => function() {
            return factory(Thread::class)->create()->id;
        },
        'body' => ucfirst($faker->word) . $faker->words(random_int(10, 50), true) . '.',
    ];
});

$factory->define(Channel::class, function($faker) {
    $name = $faker->word;
    return [
        'name' => $name,
        'slug' => $name,
    ];
});

$factory->define(\App\Favourite::class, function(Faker $faker) {
    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'favourited_id' => function() {
            return factory(Channel::class)->create()->id;
        },
        'favourited_type' => Reply::class,
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function(Faker $faker) {

    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
        'type' => \App\Notifications\ThreadWasUpdated::class,
        'notifiable_id' => function() {
            return auth()->id() ?: factory(User::class)->create()->id;
        },
        'notifiable_type' => User::class,
        'data' => ['foo' => 'bar'],
    ];

});
