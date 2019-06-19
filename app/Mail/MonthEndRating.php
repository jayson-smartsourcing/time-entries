<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MonthEndRating extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct($employee)
    {
        $this->employee = $employee;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    { 
        return $this->subject("Smartsourcing Survey")->view('poll-email')->with('employee',$this->employee);
    }
}
