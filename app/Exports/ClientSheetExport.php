<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents; // <--- Aggiungi questo
use Maatwebsite\Excel\Events\AfterSheet;   // <--- Aggiungi questo
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;

class ClientSheetExport implements FromCollection, WithHeadings, WithTitle, WithMapping, WithEvents
{
    protected $clients;
    protected $title;

    public function __construct($clients, $title)
    {
        $this->clients = $clients;
        $this->title = $title;
    }

    /**
     * Ritorna la collezione filtrata per questo foglio
     */
    public function collection()
    {
        return $this->clients;
    }

    /**
     * Questa funzione mappa esattamente i dati alle colonne.
     * Risolve il problema dello sfasamento dei titoli.
     */
    public function map($client): array
    {
        return [
            $client->id,
            $client->tipo,
            $client->nome,
            $client->cognome,
            $client->telefono,
            $client->telefono2,
            $client->indirizzo,
            $client->citta, // Assicurati che nel DB sia 'citta' senza accento
            $client->cap,
            $client->provincia,
        ];
    }

    /**
     * Intestazioni del file
     */
    public function headings(): array
    {
        return [
            'id', 'tipo', 'nome', 'cognome', 'telefono',
            'telefono2', 'indirizzo', 'città', 'cap', 'provincia',
        ];
    }

    /**
     * Imposta il nome del tab (sheet) in basso
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Registriamo l'evento AfterSheet per creare la tabella
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Calcoliamo l'ultima riga (numero di record + 1 per l'intestazione)
                $rowCount = $this->clients->count() + 1;

                // Definiamo il range della tabella (da A1 a J + ultima riga)
                // Usiamo J perché abbiamo 10 colonne
                $cellRange = 'A1:J' . $rowCount;

                // Creiamo l'oggetto Tabella di Excel
                $table = new Table($cellRange, 'Tabella_' . str_replace(' ', '_', $this->title));

                // Applichiamo uno stile predefinito (es. Medium 2)
                $tableStyle = new TableStyle();
                $tableStyle->setTheme(TableStyle::TABLE_STYLE_MEDIUM2);
                $tableStyle->setShowRowStripes(true); // Righe alternate colorate
                $tableStyle->setShowFirstColumn(false);
                $tableStyle->setShowLastColumn(false);

                $table->setStyle($tableStyle);

                // Aggiungiamo la tabella al foglio
                $event->sheet->getDelegate()->addTable($table);

                // Opzionale: Auto-dimensionamento delle colonne per leggere tutto bene
                foreach (range('A', 'J') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
