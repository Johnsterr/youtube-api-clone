<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    public function configure()
    {
        return $this->afterCreating(function (Comment $comment) {
            if ($comment->replies()->exists()) {
                return;
            }

            $comment->parent()->associate($this->findRandomCommentToMakeParentOf($comment))->save();
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'text' => ucfirst($this->faker->sentences(mt_rand(1, 3), true)),
            'user_id' => User::inRandomOrder()->first(),
            'video_id' => Video::inRandomOrder()->first(),
        ];
    }

    private function findRandomCommentToMakeParentOf(Comment $comment)
    {
        return $comment->video
            ->comments()
            ->doesntHave('parent')
            ->where('id', '<>', $comment->id)
            ->inRandomOrder()
            ->first();
    }
}
