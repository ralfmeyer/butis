<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AuswertungComponent;
use App\Livewire\MitarbeiterAbgabe;
use App\Http\Middleware\AdminMiddleware;

use App\mail\Beurteiler1AbgeschlossenMail;
use Illuminate\Support\Facades\Mail;

use App\Models\Config;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PrintBeurteilung;


Route::middleware([AdminMiddleware::class])->group(function(){
    Route::get('/logs', function() {
        return view('logs');
    })->name('logs');
});


// Route::middleware([AdminMiddleware::class])->group(function() {
Route::middleware(['auth', 'verified'])->group(function () {

    Route::redirect('/', '/beurteilung');

    Route::view('stelle', 'stelle')
    // ->middleware(['auth', 'verified'])
    ->name('stelle');

    Route::view('mitarbeiter', 'mitarbeiter')
    // ->middleware(['auth', 'verified'])
    ->name('mitarbeiter');

    Route::get('abgabe', function() {
        return view('mitarbeiterabgabe');
    })->name('mitarbeiter.abgabe');

    Route::get('abgabe/{id}', function($id) {
        return view('mitarbeiterabgabe', ['id' => $id]);
    })->name('abgabeid');

    Route::view('kriterien', 'kriterien')
    // ->middleware(['auth', 'verified'])
    ->name('kriterien');

    Route::get('/teston', function(){
        Config::setTestOn();
        return redirect('/test');
    });

    Route::get('/testoff', function(){
        Config::setTestOff();
        return redirect('/test');
    });

    Route::get('/test', function(){
        $result = 'Test ist jetzt: ' . (Config::isTest() == 1 ? "Eingeschaltet" : "Ausgeschaltet") . '<br>';

        // Gib die Links korrekt aus und füge sie zu $result hinzu
        $result .= '<a href="/teston">Test einschalten</a><br>';
        $result .= '<a href="/testoff">Test ausschalten</a><br>';

        return $result; // Return the result as a response
    })->name('test');

    Route::view('profile', 'profile')
        // ->middleware(['auth', 'verified'])
    ->name('profile');

    Route::view('startseite', 'startseite')
    // ->middleware(['auth', 'verified'])
        ->name('startseite');

    Route::get('/auswertung', AuswertungComponent::class)
    // ->middleware(['auth', 'verified'])
        ->name('auswertung');

    Route::get('/beurteilung/{id}/show', function( $id) {
        return view('beurteilungshow',  ['beurteilungId' => $id, 'mitarbeiterId' => null]);
    })->name('beurteilung.show');

    Route::get('/beurteilung/{id}/showlast', function( $id) {
        return view('beurteilungshow',  ['beurteilungId' => null, 'mitarbeiterId' => $id]);
    })->name('beurteilung.showlast');

    Route::get('/beurteilung/{mid}/create', function( $mid) {
        return view('beurteilungcreate',  ['mid' => $mid]);
    })->name('beurteilung.create');

    Route::view('/beurteilung', 'beurteilung')->name('beurteilung');

    Route::get('/print', [PrintBeurteilung::class, 'showPrintPage'])->name('print.page');

});


Route::get('/sendmail', function () {
    $details = [
        'beurteiler1anrede' => 'Herr',
        'beurteiler1name' => 'Frye',

        'beurteiler2anrede' => 'Frau',
        'beurteiler2name' => 'Honscha',

        'beurteilteranrede' => 'Max',
        'beurteiltername' => 'Meyer',

    ];



    Mail::to('mail@andreasalbers.de')->send(new Beurteiler1AbgeschlossenMail($details));

    return 'E-Mail wurde gesendet!';
});



Route::get('/setpw', function () {
    Log::info('Passwort setzen');
    $users = User::orderBy('id')->get();

    foreach ($users as $user) {
        Log::info('Passwort setzen von: ', [ $user->id, $user->name ]);

        if (!Hash::needsRehash($user->password)) {
            continue;
        }

        $user->password = Hash::make($user->password);

    }

    $user = User::where('personalnr', 10000)->first();
    $user->password = Hash::make('aTest');
    $user->save();

    return "<html><body><strong>Fertig! Version 1.04</strong></body>";
});




require __DIR__.'/auth.php';
