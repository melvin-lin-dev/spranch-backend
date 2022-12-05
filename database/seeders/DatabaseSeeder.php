<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Presentation;
use App\Models\Relation;
use App\Models\Slide;
use App\Models\SlidePart;
use App\Models\SlideStyle;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $presentation = Presentation::factory()->create(['is_main' => true]);

        $slides = Slide::factory(5)->create(['presentation_id' => $presentation->id]);

        // Update Detail for Index 0
        $presentationDetail = Presentation::factory()->create();
        $slideDetail = Slide::factory()->create(['presentation_id' => $presentationDetail->id]);
        SlideStyle::factory()->create(['slide_id' => $slideDetail->id]);
        // =========================

        $slideParts = collect([]);
        foreach ($slides as $slide) {
            SlideStyle::factory()->create(['slide_id' => $slide->id]);
            $slideParts->push(SlidePart::factory()->create(['slide_id' => $slide->id]));
        }

        $slideParts1 = collect($slideParts)->map(function ($slidePart) {
            return $slidePart->id;
        });

        $slideParts2 = collect($slideParts1);
        $firstValue = $slideParts2->splice(0, 1);
        $slideParts2->push(...$firstValue);
        for ($i = 0; $i < $slideParts1->count(); $i++) {
            Relation::factory()->create([
                'slide_part1_id' => $slideParts1[$i],
                'slide_part2_id' => $slideParts2[$i]
            ]);
        }

//        $this->call([
//
//        ]);
    }
}
