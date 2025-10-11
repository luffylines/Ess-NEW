# Holiday Management System

This system provides comprehensive holiday management functionality including automatic holiday synchronization, working days calculation, and payroll considerations for the Employee Management System.

## Features

### 1. Automatic Holiday Synchronization
- Syncs public holidays from external APIs (Calendarific)
- Fallback to manual Philippine holidays if API is unavailable
- Support for multiple countries and regions
- Automatic scheduling for regular updates

### 2. Holiday Detection & Calculations
- Check if any date is a holiday
- Calculate remaining working days in current month
- Working days calculation excluding weekends and holidays
- Double pay calculation for holidays
- Overtime pay calculation with holiday considerations

### 3. Dashboard Integration
- Real-time holiday status display
- Remaining working days counter
- Upcoming holidays display
- Holiday-aware attendance statistics

## Setup

### 1. Database Migration
```bash
php artisan migrate
```

### 2. Environment Configuration
Add to your `.env` file:
```bash
CALENDARIFIC_API_KEY=your_api_key_here
CALENDARIFIC_COUNTRY=PH
```

### 3. Initial Holiday Sync
```bash
# Sync current year holidays
php artisan holidays:sync

# Sync specific year
php artisan holidays:sync --year=2025

# Sync multiple years
php artisan holidays:sync --start-year=2024 --end-year=2026
```

## Usage Examples

### Check if Today is a Holiday
```php
use App\Models\Holiday;

$isHoliday = Holiday::isHoliday(now());
if ($isHoliday) {
    echo "Today is a holiday!";
}
```

### Calculate Remaining Working Days
```php
use App\Helpers\HolidayHelper;

$remainingDays = HolidayHelper::getRemainingWorkingDaysThisMonth();
echo "Remaining working days this month: {$remainingDays}";
```

### Calculate Overtime Pay with Holiday Consideration
```php
use App\Helpers\HolidayHelper;

$calculation = HolidayHelper::calculateOvertimePay(
    '2025-12-25', // Christmas Day
    4,            // 4 hours overtime
    500           // PHP 500 per hour
);

// Result will include double pay for holiday + overtime premium
echo "Total overtime pay: PHP " . $calculation['total_pay'];
echo "Pay type: " . $calculation['pay_type']; // 'holiday_overtime'
```

### Get Working Days Between Dates
```php
use App\Helpers\HolidayHelper;

$workingDays = HolidayHelper::getWorkingDaysBetween(
    '2025-10-01',
    '2025-10-31'
);
echo "Working days in October 2025: {$workingDays}";
```

## API Endpoints

### Check Holiday Status
```
GET /api/holidays/check?date=2025-12-25
```

Response:
```json
{
    "date": "2025-12-25",
    "is_holiday": true,
    "is_double_pay_day": true,
    "is_working_day": false,
    "holiday_details": {
        "name": "Christmas Day",
        "type": "regular",
        "description": null
    }
}
```

### Get Remaining Working Days
```
GET /api/holidays/remaining-working-days
```

Response:
```json
{
    "today": "2025-10-11",
    "end_of_month": "2025-10-31",
    "remaining_working_days": 15,
    "total_days_remaining": 20,
    "country": "PH",
    "region": null
}
```

### Calculate Overtime Pay
```
POST /api/holidays/calculate-overtime
Content-Type: application/json

{
    "date": "2025-12-25",
    "hours": 4,
    "hourly_rate": 500
}
```

Response:
```json
{
    "date": "2025-12-25",
    "calculation": {
        "base_rate": 500,
        "overtime_rate": 1250,
        "hours": 4,
        "total_pay": 5000,
        "is_holiday": true,
        "pay_type": "holiday_overtime"
    }
}
```

### Get Holidays in Date Range
```
GET /api/holidays/range?start_date=2025-10-01&end_date=2025-10-31
```

### Get Upcoming Holidays
```
GET /api/holidays/upcoming?days=30
```

## Holiday Types

### Regular Holidays
- National holidays with double pay
- Examples: Christmas, New Year, Independence Day
- Marked with red indicators in the dashboard

### Special Holidays
- Special non-working days
- May or may not have double pay (depends on company policy)
- Marked with yellow indicators in the dashboard

### Local Holidays
- Regional or city-specific holidays
- Can be filtered by region

## Payroll Integration

### Double Pay Calculation
```php
use App\Helpers\HolidayHelper;

// Check if overtime qualifies for double pay
$isDoublePayDay = HolidayHelper::isDoublePayDay('2025-12-25');

// Get monthly payroll summary including holiday considerations
$payrollSummary = HolidayHelper::getMonthlyPayrollSummary(
    $userId, 
    2025, 
    12, 
    $hourlyRate
);
```

### Working Days for Payroll
```php
// Get accurate working days for salary calculation
$workingDays = HolidayHelper::getMonthlyWorkingDays(2025, 10);

// Calculate pro-rated salary based on actual working days
$monthlyWorkingDays = 22; // Standard
$actualWorkingDays = $workingDays;
$proRatedSalary = ($monthlySalary / $monthlyWorkingDays) * $actualWorkingDays;
```

## Dashboard Features

### Holiday Status Cards
- Today's status (Holiday/Working Day)
- Remaining working days this month
- Holiday count for current month
- Upcoming holidays counter

### Holiday Calendar
- Visual display of upcoming holidays
- Holiday type indicators (Regular/Special)
- Double pay notifications
- Date and day of week information

### Attendance Statistics
- Holiday-aware attendance percentage
- Working days calculation excluding holidays
- Monthly statistics with proper working days count

## Artisan Commands

### Sync Holidays
```bash
# Basic sync (current year)
php artisan holidays:sync

# Specific year
php artisan holidays:sync --year=2026

# Multiple years
php artisan holidays:sync --start-year=2024 --end-year=2026

# Different country
php artisan holidays:sync --country=US
```

### View Command Help
```bash
php artisan holidays:sync --help
```

## Troubleshooting

### API Connection Issues
If the Calendarific API is unavailable, the system automatically falls back to manual Philippine holidays.

### Missing Holidays
Run the sync command to ensure holidays are up to date:
```bash
php artisan holidays:sync --year=2025
```

### Incorrect Working Days
Verify that holidays are properly synced and the correct country code is used in calculations.

## Extending the System

### Adding Custom Holidays
```php
use App\Models\Holiday;

Holiday::create([
    'date' => '2025-12-24',
    'name' => 'Christmas Eve (Company Holiday)',
    'type' => 'local',
    'country' => 'PH',
    'is_active' => true
]);
```

### Custom Holiday Providers
Extend the `HolidaySyncService` to support additional API providers or custom holiday sources.

### Regional Support
Add region-specific holidays by setting the `region` field in the holidays table and filtering by region in calculations.