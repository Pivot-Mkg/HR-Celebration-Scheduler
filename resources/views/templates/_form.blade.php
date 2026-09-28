<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Template Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $template->name ?? '') }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Event Type <span class="text-danger">*</span></label>
        <select name="event_type" class="form-select @error('event_type') is-invalid @enderror" required>
            <option value="birthday"     {{ old('event_type', $template->event_type ?? '') === 'birthday'     ? 'selected' : '' }}>🎂 Birthday</option>
            <option value="anniversary"  {{ old('event_type', $template->event_type ?? '') === 'anniversary'  ? 'selected' : '' }}>🌟 Work Anniversary</option>
        </select>
        @error('event_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Email Subject <span class="text-danger">*</span></label>
        <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
               value="{{ old('subject', $template->subject ?? '') }}" required placeholder="Happy Birthday, {{ '{{' }} employee_name {{ '}}' }}! 🎉">
        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">HTML Email Body <span class="text-danger">*</span></label>
        <div class="mb-1">
            <div class="p-2 rounded" style="background:#f8f9fa; font-size:0.78rem; border:1px solid #dee2e6;">
                <strong>Available variables:</strong>
                @foreach($variables as $v)
                    <code class="me-1">&#123;&#123; {{ $v }} &#125;&#125;</code>
                @endforeach
            </div>
        </div>
        <textarea name="body_html" rows="14" class="form-control font-monospace @error('body_html') is-invalid @enderror"
                  required style="font-size:0.8rem;">{{ old('body_html', $template->body_html ?? '') }}</textarea>
        @error('body_html')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Plain Text Body <span class="text-muted small">(optional)</span></label>
        <textarea name="body_text" rows="5" class="form-control font-monospace" style="font-size:0.8rem;">{{ old('body_text', $template->body_text ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">From Email <span class="text-muted small">(leave blank to use global setting)</span></label>
        <input type="email" name="from_email" class="form-control @error('from_email') is-invalid @enderror"
               value="{{ old('from_email', $template->from_email ?? '') }}" placeholder="hr@pivotmkg.com">
        @error('from_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">From Name <span class="text-muted small">(leave blank to use global setting)</span></label>
        <input type="text" name="from_name" class="form-control @error('from_name') is-invalid @enderror"
               value="{{ old('from_name', $template->from_name ?? '') }}" placeholder="HR Team">
        @error('from_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">CC Addresses</label>
        <input type="text" name="cc_addresses" class="form-control @error('cc_addresses') is-invalid @enderror"
               value="{{ old('cc_addresses', $template->cc_addresses ?? '') }}" placeholder="hr@pivotmkg.com, manager@pivotmkg.com">
        <div class="form-text">Comma-separated. Leave blank to use global setting.</div>
        @error('cc_addresses')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">BCC Addresses</label>
        <input type="text" name="bcc_addresses" class="form-control @error('bcc_addresses') is-invalid @enderror"
               value="{{ old('bcc_addresses', $template->bcc_addresses ?? '') }}" placeholder="archive@pivotmkg.com">
        <div class="form-text">Comma-separated. Leave blank to use global setting.</div>
        @error('bcc_addresses')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 d-flex gap-4">
        <div class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                   {{ old('is_active', ($template->is_active ?? true) ? '1' : '0') == '1' ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="set_default" id="set_default" class="form-check-input" value="1"
                   {{ old('set_default') ? 'checked' : '' }}>
            <label class="form-check-label" for="set_default">Set as Default for this event type</label>
        </div>
    </div>
</div>
