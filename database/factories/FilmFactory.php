<?php

namespace Database\Factories;

use App\Models\Film;
use Illuminate\Database\Eloquent\Factories\Factory;

class FilmFactory extends Factory
{
    protected $model = Film::class;

    public function definition()
    {
        return [
            'film_name'    => $this->faker->sentence(3),
            'thumbnail'    => $this->faker->imageUrl(640, 480, 'movies', true, 'Faker'),
            'duration'     => $this->faker->numberBetween(90, 180) . ' minutes',
            'review'       => $this->faker->randomFloat(1, 1, 5),
            'story_line'   => $this->faker->paragraph,
            'movie_genre'  => $this->faker->randomElement(['Action', 'Comedy', 'Drama', 'Horror', 'Sci-Fi']),
            'censorship'   => $this->faker->randomElement(['G', 'PG', 'PG-13', 'R', 'NC-17']),
            'language'     => $this->faker->languageCode,
            'director'    => $this->faker->name,
            'actor'        => $this->faker->name . ', ' . $this->faker->name . ', ' . $this->faker->name,
            'release'      => $this->faker->dateTimeBetween('-2 years', 'now'),
            'status'       => $this->faker->boolean,
        ];
    }
}
