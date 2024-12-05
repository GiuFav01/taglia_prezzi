<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

/**
 * Class ResponseHandler
 * Standardizes API responses for success, warning, info, and error handling with optional logging.
 */
class ResponseHandler
{
    /**
     * Generate a success response.
     *
     * @param string $message
     * @param mixed|null $data
     * @param bool $sendLog Whether to log the response data.
     * @return array
     */
    public static function success(string $message, $data = null, bool $sendLog = false): array
    {
        if ($sendLog) {
            Log::info('Success Response:', ['message' => $message, 'data' => $data]);
        }

        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * Generate an info response.
     *
     * @param string $message
     * @param mixed|null $data
     * @param bool $sendLog Whether to log the response data.
     * @return array
     */
    public static function info(string $message, $data = null, bool $sendLog = true): array
    {
        if ($sendLog) {
            Log::info('Info Response:', ['message' => $message, 'data' => $data]);
        }

        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * Generate a warning response.
     *
     * @param string $message
     * @param mixed|null $data
     * @param bool $sendLog Whether to log the response data.
     * @return array
     */
    public static function warning(string $message, $data = null, bool $sendLog = false): array
    {
        if ($sendLog) {
            Log::warning('Warning Response:', ['message' => $message, 'data' => $data]);
        }

        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    /**
     * Generate an error response.
     *
     * @param string $message
     * @param mixed|null $data
     * @param int|null $errorCode
     * @param bool $sendLog Whether to log the response data.
     * @return array
     */
    public static function error(string $message, $data = null, int $errorCode = null, bool $sendLog = true): array
    {
        if ($sendLog) {
            Log::error('Error Response:', [
                'message' => $message,
                'data' => $data,
                'error_code' => $errorCode,
            ]);
        }

        return [
            'success' => false,
            'message' => $message,
            'data' => $data,
            'error_code' => $errorCode,
        ];
    }
}
