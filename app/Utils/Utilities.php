<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Class Utilities
 * Provides utility functions for common tasks.
 */
class Utilities
{
    /**
     * Convert Keepa time to a Carbon instance or formatted timestamp.
     *
     * @param int $keepaTime The Keepa time value to convert.
     * @param bool $isMilliseconds Whether the input is in milliseconds.
     * @param string|null $format Optional format for the output timestamp (e.g., 'Y-m-d H:i:s').
     * @return string|null The converted timestamp in the specified format, or null on failure.
     */
    public static function convertKeepaTime(int $keepaTime, bool $isMilliseconds = false, ?string $format = 'Y-m-d H:i:s'): ?string
    {
        $baseTime = 21564000;

        try {
            // Adjust Keepa time to seconds
            $timestamp = $isMilliseconds
                ? ($keepaTime + $baseTime) * 60
                : ($keepaTime + $baseTime);

            // Create a Carbon instance from the timestamp
            $carbonDate = Carbon::createFromTimestampUTC($timestamp);

            // Return formatted date or Carbon instance
            return $format ? $carbonDate->format($format) : $carbonDate;
        } catch (\Exception $e) {
            Log::error("Error converting Keepa time: {$e->getMessage()}");
            return null;
        }
    }
}
