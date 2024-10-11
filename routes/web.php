<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\BeurteilungShow;
use App\Http\Livewire\BeurteilungEdit;
use App\Http\Controllers\BeurteilungController;
use App\Http\Middleware\AdminMiddleware;

use App\mail\Beurteiler1AbgeschlossenMail;
use Illuminate\Support\Facades\Mail;

use App\Models\Config;


Route::middleware([AdminMiddleware::class])->group(function()
{
    Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

    Route::view('stelle', 'stelle')
    ->middleware(['auth', 'verified'])
    ->name('stelle');

    Route::view('mitarbeiter', 'mitarbeiter')
    ->middleware(['auth', 'verified'])
    ->name('mitarbeiter');

    Route::view('kriterien', 'kriterien')
    ->middleware(['auth', 'verified'])
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

});


// Route::view('/', 'welcome');
Route::redirect('/', '/beurteilung');

Route::view('startseite', 'startseite')
    ->middleware(['auth', 'verified'])
    ->name('startseite');

/*
    Route::view('beurteilung', 'beurteilung')
    ->middleware(['auth', 'verified'])
    ->name('beurteilung');
*/
    Route::middleware(['auth', 'verified'])->group(function () {


//      Route::get('/beurteilung/{id}/edit', BeurteilungEdit::class)
//      ->name('beurteilung.edit');

//        Route::get('/beurteilung/{id}/show', BeurteilungController::class, 'show')
//            ->name('beurteilung.show');

        Route::get('/beurteilung/{id}/show', function( $id) {
            return view('beurteilungshow',  ['id' => $id]);
        })->name('beurteilung.show');

        Route::get('/beurteilung/{mid}/create', function( $mid) {
            return view('beurteilungcreate',  ['mid' => $mid]);
        })->name('beurteilung.create');

        Route::view('/beurteilung', 'beurteilung')->name('beurteilung');
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


require __DIR__.'/auth.php';
