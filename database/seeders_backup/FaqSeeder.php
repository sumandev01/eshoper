<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'question' => 'How can I track my order?',
                'answer' => 'You can track your order by logging into your account and clicking on the "Track Order" button on the order confirmation page.',
            ],
            [
                'question' => 'What payment methods are accepted?',
                'answer' => 'We accept a wide range of payment methods, including credit cards, debit cards, and PayPal.',
            ],
            [
                'question' => 'What is your return policy?',
                'answer' => 'We offer a 30-day return policy for most items. Please contact us to initiate a return.',
            ],
            [
                'question' => 'Can I cancel my order?',
                'answer' => 'Yes, you can cancel your order before it has been shipped. Please contact us to initiate a cancellation.',
            ],
        ];

        foreach ($data as $index => $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],

                [
                    'answer' => $faq['answer'],
                    'order' => $index + 1,
                ]
            );
        }
    }
}
