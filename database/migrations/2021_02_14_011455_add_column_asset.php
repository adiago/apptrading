<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            // $table->foreignId('asset_id')->after('remember_token')->unsigned()->nullable();
            $table->foreignId('market_id')->after('remember_token')->unsigned()->nullable();

            $table->foreign('asset_id')->references('id')->on('assets');
            $table->foreign('market_id')->references('id')->on('markets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table){
            $table->dropForeign('users_asset_id_foreign');
            $table->removeColumn('asset_id');
        });
    }
}
