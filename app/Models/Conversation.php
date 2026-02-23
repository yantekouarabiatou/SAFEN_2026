<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user1_id',
        'user2_id',
        'last_message_at'
    ];

    protected $casts = [
        'last_message_at' => 'datetime'
    ];

    /**
     * Messages de la conversation
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Dernier message de la conversation
     */
    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    /**
     * Premier participant
     */
    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    /**
     * Second participant
     */
    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    /**
     * Les deux participants
     */
    public function participants()
    {
        return User::whereIn('id', [$this->user1_id, $this->user2_id])->get();
    }

    /**
     * Obtenir l'autre participant
     */
    public function otherParticipant($userId)
    {
        return $this->user1_id == $userId ? $this->user2 : $this->user1;
    }

    /**
     * Messages non lus pour un utilisateur
     */
    public function unreadMessagesFor($userId)
    {
        return $this->messages()
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
}