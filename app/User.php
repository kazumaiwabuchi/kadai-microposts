<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
      /**
     * このユーザが所有する投稿。（ Micropostモデルとの一対多関係を定義）
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);//ユーザが多数のMicropostを所有する
    }
    /**
     * このユーザに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCounts()//ユーザが所有するmicropost,フォロー,フォロワー,お気に入り中の投稿の件数を取得
    {
        $this->loadCount(['microposts', 'followings', 'followers','favorites']);
    }
    
    /**
     * このユーザがフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
        //第一引数：得られるModelクラス,第二引数：中間テーブル名,第三引数：中間テーブルに保存されている自分のidを示すカラム名,第四引数：関係先のidを示すカラム名
    
    }

    /**
     * このユーザをフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
     /**
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしているor対象が自分自身なら何もしない
            return false;
        } else {
            // 未フォロー且つ自分自身ではないならフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function unfollow($userId)//フォローの削除
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしている且つ自分自身ではないなら、フォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローor自分自身なら実行しない
            return false;
        }
    }
    
     /**
     * 指定された $userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int  $userId
     * @return bool
     */
    public function is_following($userId)//フォローorアンフォローするか否かの判定に必要
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    /**
     * このユーザとフォロー中ユーザの投稿に絞り込む。
     */
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする,pluck()で引数に指定されたカラムの値のみ抜き出し、配列に格納
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザ（自分自身）のidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);//$userIds配列に含まれる値と一致するuser_idを持つmicropostsを返す
    }
    
    //お気に入り機能関連
    
    //このユーザがお気に入り中の多数の投稿(Micropostモデルとの多対多)
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
        //第一引数：得られるModelクラス,第二引数：中間テーブル名,第三引数：中間テーブルに保存されている自分のidを示すカラム名,第四引数：関係先のidを示すカラム名
    
    }
    
    //指定された投稿をお気に入りに追加する
    public function favorite($micropostId)
    {
        // すでにお気に入り追加しているかの確認
        $exist = $this->is_favorite($micropostId);
        
        if ($exist) {
            // すでにお気に入り済みならば何もしない
            return false;
        } else {
            // お気に入り追加していなければ追加する
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    //指定された投稿をお気に入りから削除する
    public function unfavorite($micropostId)
    {
        // すでにお気に入り済みかの確認
        $exist = $this->is_favorite($micropostId);

        if ($exist) {
            // すでにお気に入り追加済みならばお気に入りから削除
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            //お気に入りに未追加の投稿ならば何もしない
            return false;
        }
    }
    
    //指定された投稿がお気に入り追加済みか否か調べる
    public function is_favorite($micropostId)
    {
        // お気に入り済み投稿の中に、指定された投稿が存在するか
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }
    
}
