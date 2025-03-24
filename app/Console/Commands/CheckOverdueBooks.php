<?php

namespace App\Console\Commands;

use App\Models\BooksReservation;
use App\Mail\BookReturnReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckOverdueBooks extends Command
{
    protected $signature = 'books:check-overdue';
    protected $description = 'Vérifie les livres en retard et envoie des rappels par email.';

    public function handle()
    {
        $overdueReservations = BooksReservation::where('start', '<', Carbon::now()/*->subMonth()*/)
            ->whereNull('return_date')
            ->where('reminder_mail_sent', false)
            ->with(['user', 'book'])
            ->get();

        $count = 0;
        
        foreach ($overdueReservations as $reservation) {
            Mail::to($reservation->user->email)
                ->send(new BookReturnReminder($reservation));
            
            $reservation->reminder_mail_sent = true;
            $reservation->save();
            
            $count++;
        }
        
        $this->info("$count rappels de retour de livres ont été envoyés.");
        
        return Command::SUCCESS;
    }
}