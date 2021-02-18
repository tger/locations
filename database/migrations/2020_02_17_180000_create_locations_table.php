<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tipoff\Locations\Models\Market;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique()->index();
            $table->string('name')->unique(); // Internal reference name
            $table->string('abbreviation', 4)->unique(); // 3 digit abbreviation (all caps) for location. Option to add 4th digit character if necessary.
            $table->string('title_part')->nullable(); // For when have more than one location in a market, this is used to generate formal title.
            $table->string('timezone'); // Informal symbol such as EST or CST
            $table->foreignIdFor(Market::class);
            $table->boolean('corporate')->default(true); // Mark false for Miami & DC
            $table->foreignIdFor(app('fee'), 'booking_fee_id')->nullable(); // Multiple types of fees cannot be charged to a booking. We currently use a per participant fee on bookings.
            $table->foreignIdFor(app('fee'), 'product_fee_id')->nullable();
            $table->string('gmb_location')->nullable()->unique(); // GMB ID for API. Will be used to update all the other fields below.
            $table->string('gmb_account')->nullable();
            $table->string('contact_email');
            $table->string('team_names')->nullable();
            $table->foreignIdFor(app('image'), 'team_image_id')->nullable();
            $table->unsignedTinyInteger('booking_cutoff'); // Minutes before a game/slot to cutoff the booking window.
            $table->boolean('covid')->default(false); // Mark true if location closed due to COVID-19
            $table->boolean('use_iframe')->default(false); // If yes, use the booking iframe below
            $table->text('booking_iframe')->nullable(); // Iframe code for Resova/Bookeo or other 3rd parting booking software

            // Number/ID/Public Key for location account on Stripe.
            $table->string('stripe_publishable')->nullable()->unique();
            $table->text('stripe_secret')->nullable();

            // All updated from GMB so have one place as source of truth
            $table->string('title')->nullable()->unique(); // Location Title for display from GMB.
            $table->date('opened_at')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip', 5)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('monday_open')->nullable();
            $table->string('monday_close')->nullable();
            $table->string('tuesday_open')->nullable();
            $table->string('tuesday_close')->nullable();
            $table->string('wednesday_open')->nullable();
            $table->string('wednesday_close')->nullable();
            $table->string('thursday_open')->nullable();
            $table->string('thursday_close')->nullable();
            $table->string('friday_open')->nullable();
            $table->string('friday_close')->nullable();
            $table->string('saturday_open')->nullable();
            $table->string('saturday_close')->nullable();
            $table->string('sunday_open')->nullable();
            $table->string('sunday_close')->nullable();
            $table->string('maps_url')->nullable()->unique(); // URL for location's Google My Business / Google Maps page.
            $table->string('review_url')->nullable()->unique(); // URL for a new review at the location.
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('place_location')->nullable()->unique(); // Google Places ID

            $table->smallInteger('gmb_reviews')->nullable(); // Number of Google Reviews for Location
            $table->unsignedDecimal('gmb_rating', 2, 1)->nullable(); // Google Review Aggregate for Location

            $table->string('facebook')->nullable()->unique(); // Username for location's facebook page. Prefix for link: https://www.facebook.com/ with trailing backslash. Will also use for link to facebook messenger, with prefix: https://m.me/
            $table->string('tripadvisor')->nullable()->unique(); // URL for location's TripAdvisor page
            $table->string('yelp')->nullable()->unique(); // URL for location's Yelp page

            $table->foreignIdFor(app('user'), 'manager_id')->nullable();
            $table->text('waiver')->nullable(); // Waiver agreement for the location
            $table->text('waiver_minor')->nullable(); // Waiver statement for parent/legal gaurdian of minors at the location
            $table->date('closed_at')->nullable();

            $table->foreignIdFor(app('user'), 'creator_id');
            $table->foreignIdFor(app('user'), 'updater_id');
            $table->timestamps();
        });
    }
}
