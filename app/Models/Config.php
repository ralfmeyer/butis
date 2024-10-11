<?php
namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Config extends Model
{
    use HasFactory;

    // Definiere den Tabellennamen
    protected $table = 'config';

    // Primärschlüssel-Feld
    protected $primaryKey = 'id';

    // Felder, die massenweise zugewiesen werden dürfen
    protected $fillable = [
        'personalnr',
        'option',
        'value',
        'json_data', // Da auch json_data verwendet wird
    ];

    public $timestamps = true;

    /**
     * Allgemeine Methode, um Daten basierend auf den Parametern abzurufen
     */
    public static function getConfigData($option, $personalnr = null, $type = 'value')
    {
        // Erstelle einen eindeutigen Cache-Schlüssel basierend auf den Parametern
        $cacheKey = "config_{$option}_{$personalnr}_{$type}";

        // Lade die Daten aus dem Cache oder speichere sie für 60 Minuten
        $result = Cache::remember($cacheKey, 60, function () use ($option, $personalnr, $type) {
            // Führe die Query basierend auf den übergebenen Parametern aus
            $query = Config::where('option', $option);

            if ($personalnr !== null) {
                $query->where('personalnr', $personalnr);
            } else {
                $query->where('personalnr', null);
            }

            $md = $query->first();

            // Rückgabe entweder 'value' oder 'json_data'
            return $md ? ($type === 'json' ? $md->json_data : $md->value) : '';
        });
        //Cache::forget($cacheKey);
        return $result;
    }


    public static function setConfigData($option, $personalnr = null, $value = null, $json_data = null)
    {
        $cacheKey = "config_{$option}_{$personalnr}_value";

        // Zuerst prüfen, ob der Datensatz bereits existiert
        $config = Config::where('option', $option)
            ->where(function ($query) use ($personalnr) {
                $query->where('personalnr', $personalnr)->orWhereNull('personalnr');
            })
            ->first();

        // Wenn der Datensatz existiert, aktualisieren, sonst einen neuen Datensatz erstellen
        if ($config) {
            $config->value = $value;
            $config->json_data = $json_data;
            $config->save();
        } else {
            Config::create([
                'option' => $option,
                'personalnr' => $personalnr,
                'value' => $value,
                'json_data' => $json_data,
            ]);
        }

        // Den Cache aktualisieren
        Cache::forget($cacheKey);
        Cache::remember($cacheKey, 60, function () use ($value) {
            return $value;
        });
        // Log::info("Set: ", [ $cacheKey, $value ]);

        return true;
    }


    // Statische Hilfsmethoden für String und JSON
    public static function globalString($option)
    {
        return self::getConfigData($option);
    }

    public static function globalJson($option)
    {
        return self::getConfigData($option, null, 'json');
    }

    // Statische Hilfsmethoden für String und JSON - Schreiben
    public static function setGlobalString($option, $value)
    {
        return self::setConfigData($option, null, $value);
    }

    public static function setGlobalJson($option, $json_data)
    {
        return self::setConfigData($option, null, null, $json_data);
    }

    public static function personalnrString($option)
    {
        $personalnr = Auth::user()->personalnr;
        return self::getConfigData($option, $personalnr);
    }

    public static function personalnrJson($option)
    {
        $personalnr = Auth::user()->personalnr;
        return self::getConfigData($option, $personalnr, 'json');
    }

    public static function setPersonalNrString($option, $value)
    {
        $personalnr = Auth::user()->personalnr;
        return self::setConfigData($option, $personalnr, $value);
    }

    public static function setPersonalNrJson($option, $json_data)
    {
        $personalnr = Auth::user()->personalnr;
        return self::setConfigData($option, $personalnr, null, $json_data);
    }


    public static function setTestOn(){
        Config::setglobalString('IsTest', 'true');
    }
    public static function setTestOff(){
        Config::setglobalString('IsTest', 'false');
    }

    public static function isTest(){
        return Config::globalString('IsTest') === 'true';
    }

}
