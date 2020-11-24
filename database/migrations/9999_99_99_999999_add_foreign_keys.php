<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::table('visits', function (Blueprint $table) {
            
            $table -> foreign('apartment_id', 'apt-vis')
                   -> references('id')
                   -> on('apartments')
                   -> onDelete('cascade');
        });

        Schema::table('images', function (Blueprint $table) {
            
            $table -> foreign('apartment_id', 'apt-img')
                   -> references('id')
                   -> on('apartments')
                   -> onDelete('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            
            $table -> foreign('apartment_id', 'apt-msg')
                   -> references('id')
                   -> on('apartments')
                   -> onDelete('cascade');
        });

        Schema::table('apartments', function (Blueprint $table) {
            
            $table -> foreign('user_id', 'apt-usr')
                   -> references('id')
                   -> on('users')
                   -> onDelete('cascade');
        });

        Schema::table('apartment_service', function (Blueprint $table) {
            
            $table -> foreign('apartment_id', 'apt-srv')
                   -> references('id')
                   -> on('apartments')
                   -> onDelete('cascade');

            $table -> foreign('service_id', 'srv-apt')
                   -> references('id')
                   -> on('services')
                   -> onDelete('cascade'); 
        });

        Schema::table('sponsorships', function (Blueprint $table) {
            
            $table -> foreign('apartment_id', 'apt-spr')
                   -> references('id')
                   -> on('apartments')
                   -> onDelete('cascade');

            $table -> foreign('promotion_id', 'spr-pro')
                   -> references('id')
                   -> on('promotions')
                   -> onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        
        Schema::table('visits', function (Blueprint $table) {

            $table -> dropForeign('apt-vis');
        });

        Schema::table('images', function (Blueprint $table) {

            $table -> dropForeign('apt-img');
        });

        Schema::table('messages', function (Blueprint $table) {

            $table -> dropForeign('apt-msg');
        });

        Schema::table('apartments', function (Blueprint $table) {

            $table -> dropForeign('apt-usr');
        });

        Schema::table('apartment_service', function (Blueprint $table) {

            $table -> dropForeign('apt-srv');
            $table -> dropForeign('srv-apt');
        });

        Schema::table('sponsorships', function (Blueprint $table) {
            
            $table -> dropForeign('apt-spr');
            $table -> dropForeign('spr-pro');
        });
    }
}
