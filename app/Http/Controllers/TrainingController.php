<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitQuestionsRequest;
use App\Models\{TrainingSession, Topic, Question, Attempt, Answer};
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class TrainingController extends Controller
{
    public function showTrainingStateAction(TrainingSession $session): Response|RedirectResponse
    {
        $user = Auth::user();
        $this->authorizeSession($session, $user);

        if ($session->attempts()->doesntExist()) {
            return redirect()
                ->route('training.questions.show', ['session' => $session->id])
                ->with('info', 'Please complete your training.');
        }

        $topics = $user->role->topics()->orderBy('name')->get();
        $stats = $topics->map(function (Topic $topic) use ($session) {
            $attempt = $session->attempts()
                ->where('topic_id', $topic->id)
                ->latest('completed_at')
                ->first();

            return [
                'topic'       => $topic,
                'score'        => $attempt->score ?? null,
                'passed'       => $attempt->passed ?? false,
                'completed_at' => $attempt->completed_at ?? null,
                'retake_url'   => ($attempt->passed ?? false)
                    ? null
                    : route('training.questions.show', [
                        'session' => $session->id, 'topic' => $topic->id
                    ]),
            ];
        });

        $allPassed = $stats->every(fn($s) => $s['passed']);

        return Inertia::render(
            'training/show-training-state',
            compact('session', 'stats', 'allPassed')
        );
    }

    public function showQuestionsAction(TrainingSession $session = null, Topic $topic = null): Response
    {
        $user = Auth::user();

        if (! $session) {
            $session = TrainingSession::create([
                'user_id'    => $user->id,
                'started_at' => Carbon::now(),
            ]);
        }

        $this->authorizeSession($session, $user);

        $assignedIds = $user->role->topics->pluck('id')->toArray();
        if ($topic) {
            if (! in_array($topic->id, $assignedIds)) {
                abort(403, 'Cannot access that topic.');
            }
            $topics = collect([$topic]);
        } else {
            $topics = $user->role->topics;
        }

        $questions = Question::whereIn('topic_id', $topics->pluck('id'))
            ->with('options')
            ->get();

        return Inertia::render(
            'training/show-questions',
            compact('session', 'topic', 'questions')
        );
    }

    public function submitQuestionsAction(
        SubmitQuestionsRequest $request,
        TrainingSession $session,
        Topic $topic = null
    ): RedirectResponse
    {
        $user = Auth::user();
        $this->authorizeSession($session, $user);

        $answers = $request->validated()['answers'] ?? [];

        $assignedIds = $user->role->topics->pluck('id')->toArray();
        if ($topic) {
            if (! in_array($topic->id, $assignedIds)) {
                abort(403, 'Cannot access that topic.');
            }
            $topics = collect([$topic]);
        } else {
            $topics = $user->role->topics;
        }

        DB::transaction(function () use ($topics, $answers, $session, $user) {
            $now = Carbon::now();

            foreach ($topics as $mod) {
                $questions = Question::with('options')
                    ->where('topic_id', $mod->id)
                    ->get();

                $total   = $questions->count();
                $correct = $questions->reduce(function ($carry, $q) use ($answers) {
                    $correctIds = $q->options
                        ->where('is_correct', true)
                        ->pluck('id')
                        ->sort()->values()->all();
                    $picked     = collect(
                        $answers[$q->id] ?? []
                    )->map('intval')
                        ->sort()->values()->all();

                    return $carry + ($picked === $correctIds);
                }, 0);

                $score  = $total ? round(($correct / $total) * 100) : 0;
                $passed = $score >= $mod->required_score;

                $attempt = Attempt::create([
                    'user_id'             => $user->id,
                    'topic_id'           => $mod->id,
                    'training_session_id' => $session->id,
                    'started_at'          => $now,
                    'completed_at'        => $now,
                    'score'               => $score,
                    'passed'              => $passed,
                ]);

                foreach ($answers as $questionId => $optionIds) {
                    foreach ($optionIds as $optId) {
                        Answer::create([
                            'attempt_id' => $attempt->id,
                            'option_id'  => $optId,
                        ]);
                    }
                }
            }

            $pending = $user->role->topics->pluck('id')
                ->diff($session->attempts()->where('passed', true)->pluck('topic_id'));

            if ($pending->isEmpty()) {
                $session->update(['completed_at' => Carbon::now()]);
            }
        });

        return redirect()
            ->route('training.show', ['session' => $session->id])
            ->with('success', 'Your answers have been submitted.');
    }

    public function watchTutorialAction(TrainingSession $session = null, Topic $topic = null): Response
    {
        return Inertia::render(
            'training/watch-tutorial',
            compact('session', 'topic')
        );
    }

    private function authorizeSession(TrainingSession $session, $user): void
    {
        abort_unless($session->user_id === $user->id, 403, 'Unauthorized session.');
    }
}
