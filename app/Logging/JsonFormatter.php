<?php

namespace App\Logging;

use Illuminate\Support\Arr;
use Monolog\Formatter\JsonFormatter as BaseJsonFormatter;

class JsonFormatter extends BaseJsonFormatter
{
    protected $includeStacktraces = true;

    /**
     * {@inheritdoc}
     */
    public function format(array $record): string
    {
        $content = [
            'message' => $record['message'],
            'method'  => $this->method(),
        ];

        if (Arr::has($record, 'context')) {
            $content = array_merge($content, $record['context']);
        }

        $record = [
            'level'    => $record['level_name'],
            'time'     => $record['datetime']->format('Y-m-d H:i:s.u'),
            'trace_id' => $this->traceId(),
            'type'     => 'php',
            'project'  => env("APP_NAME", ''),
            'content'  => $content,
        ];

        return $this->toJson($this->normalize($record)) . ($this->appendNewline ? "\n" : '');
    }

    private function method()
    {
        return $_SERVER['REQUEST_METHOD'] ?? "";
    }

    private function traceId()
    {
        if (isset($_SERVER['TRACE_ID'])) {
            return $_SERVER['TRACE_ID'];
        } elseif (isset($_SERVER['HTTP_TRACE_ID'])) {
            return $_SERVER['HTTP_TRACE_ID'];
        } else {
            return "";
        }
    }
}
