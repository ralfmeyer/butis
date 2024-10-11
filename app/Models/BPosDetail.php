<?php

namespace App\Models;


class BPosDetail
{
    public $kriterium;
    public $bPos;

    public function __construct($kriterium, $bPos)
    {
        $this->kriterium = $kriterium;
        $this->bPos = $bPos;
    }

    public static function fromKriteriumAndBPos($kriterium, $bPos)
    {
        $result = new self($kriterium, [
            'id' => $bPos->id,
            'beurteilungid' => $bPos->beurteilungid,
            'beurteilungsmerkmalid' => $bPos->beurteilungsmerkmalid,
            'beurteiler1note' => $bPos->beurteiler1note,
            'beurteiler2note' => $bPos->beurteiler2note,
            'beurteiler1bemerkung' => $bPos->beurteiler1bemerkung,
            'beurteiler2bemerkung' => $bPos->beurteiler2bemerkung,
            'zusatz' => $bPos->zusatz,
            'beurteiler1laenderung' => $bPos->beurteiler1laenderung,
            'beurteiler2laenderung' => $bPos->beurteiler2laenderung,
            'beurteiler1bemerkungError' => false,
            'beurteiler2bemerkungError' => false,
        ]);
        return $result;
    }
}
