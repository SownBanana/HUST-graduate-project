<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mention extends Model
{
    //
    /**
     * Get the messages that owns the Mention
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function messages()
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * Get the user that owns the Mention
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
