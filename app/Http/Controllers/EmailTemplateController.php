<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Employee;
use App\Models\Setting;
use App\Services\TemplateRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{
    public function __construct(private TemplateRenderer $renderer) {}

    public function index()
    {
        $templates = EmailTemplate::withTrashed()->orderBy('event_type')->orderBy('name')->get();
        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create', ['variables' => TemplateRenderer::VARIABLES]);
    }

    public function store(Request $request)
    {
        $data = $this->validateTemplate($request);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $template = EmailTemplate::create($data);

        if ($request->boolean('set_default')) {
            $template->setAsDefault();
        }

        return redirect()->route('templates.show', $template)
            ->with('success', 'Template created successfully.');
    }

    public function show(EmailTemplate $template)
    {
        return view('templates.show', compact('template'));
    }

    public function edit(EmailTemplate $template)
    {
        return view('templates.edit', [
            'template'  => $template,
            'variables' => TemplateRenderer::VARIABLES,
        ]);
    }

    public function update(Request $request, EmailTemplate $template)
    {
        $data = $this->validateTemplate($request);
        $data['updated_by'] = Auth::id();
        $template->update($data);

        if ($request->boolean('set_default')) {
            $template->setAsDefault();
        }

        return redirect()->route('templates.show', $template)
            ->with('success', 'Template updated successfully.');
    }

    public function destroy(EmailTemplate $template)
    {
        $template->update(['is_active' => false, 'is_default' => false]);
        $template->delete();
        return redirect()->route('templates.index')
            ->with('success', 'Template deactivated and archived.');
    }

    public function setDefault(EmailTemplate $template)
    {
        $template->setAsDefault();
        return back()->with('success', "'{$template->name}' set as default {$template->event_type} template.");
    }

    public function duplicate(EmailTemplate $template)
    {
        $clone = $template->duplicate();
        return redirect()->route('templates.edit', $clone)
            ->with('success', 'Template duplicated. Edit and save.');
    }

    public function preview(Request $request, EmailTemplate $template)
    {
        $request->validate(['employee_id' => 'nullable|exists:employees,id']);

        $employee = $request->employee_id
            ? Employee::findOrFail($request->employee_id)
            : Employee::active()->first();

        if (!$employee) {
            return back()->with('error', 'No active employees to preview with.');
        }

        $rendered = $this->renderer->renderTemplate($template, $employee, $template->event_type);
        $config   = Setting::getMailConfig();
        $from     = $template->from_email ?: ($config['from_address'] ?? config('mail.from.address'));
        $fromName = $template->from_name  ?: ($config['from_name']    ?? config('mail.from.name'));
        $cc       = $template->cc_addresses ?: ($config['cc'] ?? '');
        $bcc      = $template->bcc_addresses ?: ($config['bcc'] ?? '');

        $employees = Employee::active()->orderBy('employee_name')->get();

        return view('templates.preview', compact(
            'template', 'employee', 'rendered', 'from', 'fromName', 'cc', 'bcc', 'employees'
        ));
    }

    private function validateTemplate(Request $request): array
    {
        return $request->validate([
            'name'          => 'required|string|max:255',
            'event_type'    => 'required|in:birthday,anniversary',
            'subject'       => 'required|string|max:255',
            'body_html'     => 'required|string',
            'body_text'     => 'nullable|string',
            'from_email'    => 'nullable|email|max:255',
            'from_name'     => 'nullable|string|max:255',
            'cc_addresses'  => 'nullable|string|max:1000',
            'bcc_addresses' => 'nullable|string|max:1000',
            'is_active'     => 'boolean',
        ]);
    }
}
