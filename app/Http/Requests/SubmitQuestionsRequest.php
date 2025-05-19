<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class SubmitQuestionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user    = Auth::user();
        $topic  = $this->route('topic');
        $topics = $topic
            ? collect([$topic])
            : $user->role->topics;


        $questionIds = Question::whereIn('topic_id', $topics->pluck('id'))
            ->pluck('id')
            ->toArray();

        $rules = [
            'answers'             => ['required', 'array'],
        ];

        foreach ($questionIds as $qId) {
            $rules["answers.{$qId}"]   = ['required', 'array', 'min:1'];
            $rules["answers.{$qId}.*"] = [
                'integer',
                'distinct',
                Rule::exists('options', 'id')->where('question_id', $qId),
            ];
        }

        return $rules;
    }
}
