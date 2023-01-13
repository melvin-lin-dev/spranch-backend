<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Presentation;
use App\Models\PresentationStyle;
use App\Models\Relation;
use App\Models\RelationStyle;
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
        $presentation = Presentation::factory()->create(['is_main' => true]);
        PresentationStyle::factory()->create(['presentation_id' => $presentation->id]);

        $slides = Slide::factory(5)->create(['presentation_id' => $presentation->id]);

        // Update Detail for Index 0
        $presentationDetail = Presentation::factory()->create();
        PresentationStyle::factory()->create(['presentation_id' => $presentationDetail->id]);
        $slideDetail = Slide::factory()->create(['presentation_id' => $presentationDetail->id, 'is_first' => true]);
        SlideStyle::factory()->create(['slide_id' => $slideDetail->id, 'z_index' => $slides->count() + 1]);

        $slides[0]->is_first = true;
        $slides[0]->detail_id = $presentationDetail->id;
        $slides[0]->save();
        // =========================

        $slideParts = collect([]);
        foreach ($slides as $index => $slide) {
            SlideStyle::factory()->create(['slide_id' => $slide->id, 'z_index' => $index + 1]);
            $slideParts->push(SlidePart::factory()->create(['slide_id' => $slide->id]));
        }

        $slideParts1 = collect($slideParts)->map(function ($slidePart) {
            return $slidePart->id;
        });

        $slideParts2 = collect($slideParts1);
        $firstValue = $slideParts2->splice(0, 1);
        $slideParts2->push(...$firstValue);
        for ($i = 0; $i < $slideParts1->count(); $i++) {
            $relation = Relation::factory()->create([
                'presentation_id' => $presentation->id,
                'slide_part1_id' => $slideParts1[$i],
                'slide_part2_id' => $slideParts2[$i],
            ]);
            RelationStyle::factory()->create(['relation_id' => $relation->id]);
        }
    }
}
