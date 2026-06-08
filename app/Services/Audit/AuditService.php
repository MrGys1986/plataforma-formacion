<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuditService
{
    public function log(
        string $module,
        string $action,
        mixed $entity = null,
        array $oldValues = [],
        array $newValues = [],
    ): void {
        try {
            $request = app()->bound('request') ? request() : null;

            AuditLog::create([
                'user_id' => auth()->id(),
                'module' => $module,
                'action' => $action,
                'entity_type' => $entity instanceof Model ? $entity->getMorphClass() : null,
                'entity_id' => $entity instanceof Model ? $entity->getKey() : null,
                'old_values' => $this->redact($oldValues) ?: null,
                'new_values' => $this->redact($newValues) ?: null,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::warning('No fue posible registrar la auditoría.', [
                'module' => $module,
                'action' => $action,
                'exception' => $exception->getMessage(),
            ]);
        }
    }

    private function redact(array $values): array
    {
        $sensitiveKeys = [
            'password', 'password_confirmation', 'curp', 'email', 'phone',
            'token', 'secret', 'json_payload', 'external_response', 'path',
            'stored_name', 'payment_reference',
        ];

        foreach ($values as $key => $value) {
            if (in_array(strtolower((string) $key), $sensitiveKeys, true)) {
                $values[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $values[$key] = $this->redact($value);
            }
        }

        return $values;
    }
}
