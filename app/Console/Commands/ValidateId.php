<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ValidateId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while(true) {
            $id = $this->ask('Enter ID number');
            if($id== 'exit') {
                break;
            }
            $this->info($this->isValidNumber($id) ? 'Valid ID' : 'Invalid ID');
        }
    }

    private function isValidNumber(?string $num, ?string $country = 'IL'): bool {
        // Return false if it is not 9 character string
        if(strlen($num) != 9) {
            return false;
        }
        // To check later for country policy (if any)
        $country_policy = [
            'ES' => 10,
            'PR' => 8
        ];
        if($country && isset($country_policy[$country])) {
            // Check if the sum of the first two digits
            $sum = intval(substr($num, 0, 1)) + intval(substr($num, 1, 1));
            // check if the sum is greater than the country policy
            if($sum > $country_policy[$country]) {
                return false;
            }
        }

        $aggr = 0;
        // Loop through the string
        for ($i = 0; $i < strlen($num); $i++) {
            // Take the character at position $i
            $char = substr($num, $i, 1);
            // Multiply by 1 or 2 depending on the position
            $mul = (1 + ($i % 2)) * intval($char);
            // If the result is greater than 9, subtract 9
            if ($mul > 9) {
                $mul -= 9;
            }
            // Add the result to the aggregate
            $aggr += $mul;
        }
        // Check if the aggregate is divisible by 10
        $isVal = ($aggr % 10 == 0);
        return $isVal;
    }

}
