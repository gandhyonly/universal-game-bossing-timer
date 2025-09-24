<?php

namespace App\Console\Commands;

use App\Models\Timer;
use App\Services\DiscordNotifier;
use Illuminate\Console\Command;

class NotifyUpcomingSpawns extends Command
{
    protected $signature = 'timers:notify-upcoming';

    protected $description = 'Send Discord notifications for timers spawning in <= 3 minutes';

    public function handle(DiscordNotifier $notifier): int
    {
        $now = now();
        $upperBound = $now->copy()->addMinutes(3);

        $timers = Timer::query()
            ->whereNotNull('spawn_at')
            ->where('spawn_at', '>', $now)
            ->where('spawn_at', '<=', $upperBound)
            ->where('notified_two_min', false)
            ->get();

        foreach ($timers as $timer) {
            $diffSeconds = $timer->spawn_at->diffInSeconds($now);
            $diffSeconds = abs($diffSeconds);
            \Log::info("now: {$now}");
            \Log::info("spawn_at: {$timer->spawn_at}");
            \Log::info("Diff seconds: {$diffSeconds}");
            $minutes = intdiv($diffSeconds, 60);
            $seconds = $diffSeconds % 60;

            $message = sprintf(
                '@LINEAGE2M :alarm_clock: %s spawns in %dm %ds at %s (lvl %s, %dm).',
                $timer->name,
                $minutes,
                $seconds,
                $timer->location ?? 'unknown',
                $timer->level ?? 'N/A',
                $timer->delay_minutes
            );

            $ok = $notifier->send($message);
            if ($ok) {
                $timer->notified_two_min = true;
                $timer->save();
                $this->info("Notified: {$timer->name}");
            } else {
                $this->warn("Failed to notify: {$timer->name}");
            }
        }

        return self::SUCCESS;
    }
}


