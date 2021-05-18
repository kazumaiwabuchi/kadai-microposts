<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content'];

    /**
     * この投稿を所有するユーザ。（ Userモデルと一対多関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
     //この投稿をお気に入り中の多数のユーザ(Userモデルとの多対多)
    public function favorite_users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id', 'user_id')->withTimestamps();
        //第一引数：得られるModelクラス,第二引数：中間テーブル名,第三引数：中間テーブルに保存されている自分のidを示すカラム名,第四引数：関係先のidを示すカラム名
    
    }
    
    
}
