<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_follow', function (Blueprint $table) { //中間テーブルの作成
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id'); //user_idカラム
            $table->unsignedBigInteger('follow_id'); //follow_idカラム
            $table->timestamps();
            
        // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');//usersテーブルのidカラムと紐づけ,cascadeで、参照先データが消えたら一緒に削除するよう設定
            $table->foreign('follow_id')->references('id')->on('users')->onDelete('cascade');

        // user_idとfollow_idの組み合わせの重複を許さない(一度保存したフォロー関係を何度も重複保存しないため)
            $table->unique(['user_id', 'follow_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_follow');
    }
}
