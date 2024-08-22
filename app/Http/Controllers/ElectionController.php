<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,Election,Vote};
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\QuestionActivated;
use Illuminate\Support\Str;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::OrderBy('id','DESC')->get();
        return view('elections.index')->with(compact('elections'));
    }

    public function create()
    {
        $users = User::where(['type' => 'user', 'is_active' => '1'])->get();
        return view('elections.create')->with(compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // 'start_date' => 'required|date|after:now',
            // 'end_date' => 'required|date|after:start_date',
            'type' => 'required|array',
            'type.*' => 'in:multiple-choice,first-past-the-post,preferential',
            'candidates' => 'required|array',
            'candidates.*' => 'exists:users,id'
        ]);
        $slug = Str::slug($request->title, '-');
        $originalSlug = $slug;
        $counter = 1;
        while (Election::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $election = Election::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => implode(",",$request->type),
            'slug' => $slug
        ]);

        $election->candidates()->attach($request->candidates);

        return to_route('election')->with('success', 'Election created successfully.');
    }

    public function edit($id)
    {
        $election = Election::findOrFail($id);
        $candidates = $election->candidates;
        $users = User::where(['type' => 'user', 'is_active' => '1'])->get();

        return view('elections.edit')->with(compact('election', 'candidates','users'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // 'start_date' => 'required|date|after:now',
            // 'end_date' => 'required|date|after:start_date',
            'type' => 'required|array',
            'type.*' => 'in:multiple-choice,first-past-the-post,preferential',
            'candidates' => 'required|array',
            'candidates.*' => 'exists:users,id'
        ]);

        $election = Election::findOrFail($request->id);
        if ($election->title !== $request->title) {
            $slug = Str::slug($request->title, '-');
            $originalSlug = $slug;
            $counter = 1;
            while (Election::where('slug', $slug)->where('id', '!=', $election->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        } else {
            $slug = $election->slug;
        }

        $election->update([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => implode(",", $request->type),
        ]);

        $election->candidates()->sync($request->candidates);

        return to_route('election')->with('success', 'Election updated successfully.');
    }


    public function detail($id)
    {
        $election = Election::findOrFail($id);
        $candidates = $election->candidates;

        return view('elections.detail')->with(compact('election', 'candidates'));
    }

    public function destroy(Request $request)
    {
        Election::destroy($request->id);
        return to_route('election')->with('success', 'Election deleted successfully.');
    }

    public function addQuestion($id)
    {
        $election = Election::findOrFail($id);
        $questions = Question::where('election_id', $id)->OrderBy('id','DESC')->get();

        return view('elections.questions.add-question')->with(compact('id','election','questions'));
    }

    public function questionStatus(Request $request)
    {
        $question = Question::findOrFail($request->id);
        $question->is_active = $question->is_active == '1' ? '0' : '1';
        $question->save();
        // Broadcast the event
        event(new QuestionActivated($question));


        return back()->with('success', 'Question status changed successfully.');
    }

    public function questionStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string|max:255',
            'question_type' => 'required|in:text,radio,checkbox,range',
            'range_min' => 'nullable|integer|min:0',
            'range_max' => 'nullable|integer|gte:range_min',
            'options.*' => 'required_if:question_type,radio,checkbox|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $question = Question::create([
            'user_id' => Auth::id(),
            'election_id' => $request->election_id,
            'question' => $request->question_text,
            'type' => $request->question_type,
            'range_min' => $request->question_type === 'range' ? $request->range_min : null,
            'range_max' => $request->question_type === 'range' ? $request->range_max : null,
            'is_active' => '0',
        ]);

        if (in_array($request->question_type, ['radio', 'checkbox'])) {
            foreach ($request->options as $option) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option' => $option,
                ]);
            }
        }

        return back()->with('success', 'Question added successfully.');
    }


    public function editQuestion($election_id,$question_id)
    {
        $election = Election::findOrFail($election_id);
        $question = Question::findOrFail($question_id);

        return view('elections.questions.edit-question')->with(compact('election','question'));
    }

    public function updateQuestion(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:255',
            'question_type' => 'required|string|in:text,radio,checkbox,range',
            'range_min' => 'nullable|numeric',
            'range_max' => 'nullable|numeric',
            'options.*' => 'nullable|string|max:255',
        ]);

        // Find the question by ID
        $question = Question::findOrFail($request->question_id);

        // Update question details
        $question->question = $request->input('question_text');
        $question->type = $request->input('question_type');

        // Handle range-specific data
        if ($request->input('question_type') === 'range') {
            $question->range_min = $request->input('range_min');
            $question->range_max = $request->input('range_max');
        } else {
            $question->range_min = null;
            $question->range_max = null;
        }

        $question->save();

        if (in_array($request->input('question_type'), ['radio', 'checkbox'])) {
            $existingOptions = $question->options->pluck('option')->toArray();
            $newOptions = $request->input('options', []);

            foreach ($question->options as $option) {
                if (!in_array($option->option, $newOptions)) {
                    $option->delete();
                }
            }

            foreach ($newOptions as $option) {
                if (!in_array($option, $existingOptions)) {
                    $question->options()->create(['option' => $option]);
                }
            }
        }

        return to_route('election.addQuestion', ['id' => $request->election_id])->with('success', 'Question updated successfully!');
    }


    public function questionDestroy(Request $request)
    {
        $question = Question::findOrFail($request->id);
        $question->delete();
        return back()->with('success', 'Question deleted successfully.');
    }

    public function view_results($id)
    {
        $election = Election::with('questions.answers.vote.user')->findOrFail($id);

        $votes = Vote::with(['user', 'answers.question'])
            ->where('election_id', $id)
            ->Orderby('id','DESC')
            ->get();

        return view('elections.view_result')->with(compact('election','votes'));
    }
}
