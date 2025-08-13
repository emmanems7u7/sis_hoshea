<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;
use \App\Models\ConfCorreo;
class TwoFactorController extends Controller
{
    public function index()
    {
        if (!session('two_factor')) {
            return redirect()->route('login');
        }

        return view('auth.twofactor');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|array|min:6|max:6',
            'code.*' => 'numeric',
        ]);

        $code = implode('', $request->input('code'));

        $user = User::find(session('two_factor'));

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->two_factor_code !== $code || $user->two_factor_expires_at->lt(now())) {
            return back()->withErrors(['code' => 'El código es incorrecto o ha expirado.']);
        }

        $user->resetTwoFactorCode();
        Auth::login($user);

        session()->forget('two_factor');

        return redirect()->intended('/home');
    }
    public function resend()
    {
        $conf = ConfCorreo::first();

        config([
            'mail.mailers.smtp.host' => $conf->host,
            'mail.mailers.smtp.port' => $conf->port,
            'mail.mailers.smtp.username' => $conf->username,
            'mail.mailers.smtp.password' => $conf->password,
            'mail.mailers.smtp.encryption' => $conf->encryption,
            'mail.default' => $conf->mailer,

            'mail.from.address' => $conf->from_address,
            'mail.from.name' => $conf->from_name,
        ]);


        $user = User::find(session('two_factor'));

        if (!$user) {
            return redirect()->route('login');
        }

        $user->generateTwoFactorCode();
        Mail::to($user->email)->send(new TwoFactorCodeMail($user));

        return back()->with('message', 'Se ha reenviado un nuevo código a tu correo.');
    }


}
