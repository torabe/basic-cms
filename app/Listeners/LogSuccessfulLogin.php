<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Login;
use App\Models\Log;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if (get_class($event->user) !== 'App\Models\Admin') return;

        // adminsテーブルのupdated_atカラムのみを更新します
        $event->user->touch();

        // ログオン記録を残します
        $log = new Log;
        $log->login_id = Auth::guard('admin')->user()->login_id;
        $log->name = Auth::guard('admin')->user()->name;
        $log->ip = request()->ip();
        $log->user_agent = request()->header('User-Agent');
        $log->save();
    }
}
