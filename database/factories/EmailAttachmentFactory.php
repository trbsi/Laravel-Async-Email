<?php

namespace Database\Factories;

use App\Models\EmailAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailAttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmailAttachment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'attachment' => sprintf('email_attachments/2021-01-29/%s.jpg', $this->faker->word)
        ];
    }
}
