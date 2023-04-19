## Morning Task

### Start
The files relevant to this task are:
```
app/Http/Console/Commands/ValidateId.php
tests/Feature/ValidateTest.php
```

To run the validator, run the following command:
```bash
php artisan validate:id
```

### 1. Code Review
The code looks good apart of these things:

1. Instead of writing the code insife `if` block i would do this
   ```php
   if($length != 9) {
       return false;
   }
   ```
2. Instead of running on the string on for and separating the characters with `substr($num, $i, 1)` I would run on the string with `foreach()` as array and handle each character.
3. There is no comments in the code

### 2. Bug Fix
1. The variable `$length` was not defined
2. This if block `if ($mul >= 9)` is wrong because it hurts the ID numbers with a 9 in the "1" itegration (like `024411910`)
3. The `$aggr` variable was incremented after the for loop, so it was calculating only the last number, that's the cause it succeeded with IDs like `322213620` and `024411910` but not on `029060183`

### 3. Spain and Portugal validation
In order to fit the code to the spanish and portuguese ID,
I added an optional parameter to the function that checks the country, and check if the sum of the first two digits exceeds the number defined in the policy
    
```php
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
```

### 4. Tests and Implementation
In this case we are using Laravel's Unit test system
```php
    public function test_example(): void
    {
        $validor = new IdValidator();
        
        // Check Israeli number cases
        $this->assertTrue($validor->isValidNumber('322213620'));
        $this->assertTrue($validor->isValidNumber('024411910'));
        $this->assertTrue($validor->isValidNumber('029060183'));
        // Check Spain
        $this->assertTrue($validor->isValidNumber('322213620', 'ES'));
        // Check Portugal
        $this->assertTrue($validor->isValidNumber('322213620', 'PR'));
    }
```
