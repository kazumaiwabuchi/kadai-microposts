<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('micropost_id');
            $table->timestamps();
            
            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');//usersテーブルのidカラムと紐づけ,cascadeで、参照先データが消えたら一緒に削除するよう設定
            $table->foreign('micropost_id')->references('id')->on('microposts')->onDelete('cascade');//micropostsテーブルのidカラムと紐づけ

            // user_idとmicropost_idの組み合わせの重複を許さない(同じ投稿を重複してお気に入り追加しないため)
            $table->unique(['user_id', 'micropost_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
}
