<?php

namespace App\Http\View\Composers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LayoutComposer
{
    protected $username;
    protected $email;
    protected $userId;

    public function __construct(Request $request)
    {
        $this->username = $request->username;
        $this->email = $request->email;
        $this->userId = $request->id;
    }

    public function compose(View $view)
    {
        $view->with('username', $this->username);
        $view->with('email', $this->email);
        $view->with('userId', $this->userId);
    }
}
