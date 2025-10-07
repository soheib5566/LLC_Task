<?php

namespace App\Http\Requests;

use App\Jobs\ApplicationEmailJob;
use App\Models\Application;
use App\Models\File as ApplicationFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ApplicationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'phone' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^\+[1-9]\d{1,14}$/', $value)) {
                        $fail('The ' . $attribute . ' must be in E.164 format.');
                    }
                },
            ],
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'country' => 'required|string|min:2',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'comments' => 'nullable|string',
        ];
    }

    public function storeApplication()
    {
        return DB::transaction(function () {
            $application = Application::create([
                'email' => $this->input('email'),
                'phone' => $this->input('phone'),
                'date_of_birth' => $this->input('date_of_birth'),
                'gender' => $this->input('gender'),
                'country' => $this->input('country'),
                'comments' => $this->input('comments') ?? null,
                'user_id' => Auth::id(),
            ]);

            $filesPayload = [];
            if ($this->has('files')) {
                foreach ($this->file('files') as $file) {
                    $original = $file->getClientOriginalName();
                    $path = Storage::disk(name: 'public')->putFileAs(path: 'applications', file: $file, name: $original);

                    $application->files()->create([
                        'path' => $path,
                        'original_name' => $original,
                        'application_id' => $application->id
                    ]);

                    $filesPayload[] = [
                        'original_name' => $original,
                        'path' => $path,
                        'mime_type' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ];
                }
            }

            $data = $application->only([
                'id',
                'email',
                'phone',
                'date_of_birth',
                'gender',
                'country',
                'comments',
                'created_at'
            ]);
            $user = null;
            if ($userModel = Auth::user()) {
                $user = [
                    'id' => $userModel->id,
                    'name' => $userModel->name,
                    'email' => $userModel->email,
                    'created_at' => $userModel->created_at->toDateTimeString(),
                ];
            }
            ApplicationEmailJob::dispatch($data, $user, $filesPayload);
        });
    }
}
