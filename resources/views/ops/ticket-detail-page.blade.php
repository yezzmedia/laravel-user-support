<x-filament-panels::page>
    @php
        $detail = $this->detail;
        $isClosed = ($detail['status'] ?? '') === 'closed';
        $statusLabel = match ($detail['status'] ?? '') {
            'open' => 'Open', 'replied' => 'Replied', 'closed' => 'Closed', default => $detail['status'] ?? '',
        };
        $statusBg = match ($detail['status'] ?? '') {
            'open' => '#fffbeb', 'replied' => '#f0f9ff', 'closed' => '#f3f4f6', default => '#f3f4f6',
        };
        $statusFg = match ($detail['status'] ?? '') {
            'open' => '#b45309', 'replied' => '#0369a1', 'closed' => '#4b5563', default => '#4b5563',
        };
    @endphp

    <div style="border:1px solid #e5e7eb; background:#fff; border-radius:0.25rem; padding:1rem; margin-bottom:1rem;">
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:0.75rem; font-size:0.875rem;">
            <div>
                <div style="font-weight:600; color:#6b7280; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">Ticket</div>
                <div style="color:#111827; font-family:monospace; font-weight:500;">#{{ $detail['ticket_number'] }}</div>
            </div>
            <div>
                <div style="font-weight:600; color:#6b7280; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">Status</div>
                <span style="font-size:0.75rem; font-weight:500; background:{{ $statusBg }}; color:{{ $statusFg }}; padding:0.125rem 0.5rem; border-radius:0.25rem;">{{ $statusLabel }}</span>
            </div>
            <div>
                <div style="font-weight:600; color:#6b7280; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">User</div>
                <div style="color:#111827;">{{ $detail['user_name'] }}</div>
            </div>
            <div>
                <div style="font-weight:600; color:#6b7280; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">Email</div>
                <div style="color:#111827; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $detail['user_email'] }}</div>
            </div>
            <div style="grid-column:span 2;">
                <div style="font-weight:600; color:#6b7280; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">Subject</div>
                <div style="color:#111827; font-weight:500;">{{ $detail['subject'] }}</div>
            </div>
            <div style="grid-column:span 2;">
                <div style="font-weight:600; color:#6b7280; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em;">Opened</div>
                <div style="color:#111827;">{{ $detail['created_at'] }}</div>
            </div>
        </div>
    </div>

    <div style="border:1px solid #e5e7eb; background:#fff; border-radius:0.25rem;">
        <div style="padding:1rem; max-height:24rem; overflow-y:auto;">
            @forelse ($detail['conversation'] as $msg)
                @php $isAdmin = $msg['sender_type'] === 'admin'; @endphp
                <div style="display:flex; flex-direction:column; max-width:80%; border:1px solid; border-radius:0.5rem; padding:0.5rem 0.75rem; font-size:0.875rem; margin-bottom:0.75rem;
                    @if ($isAdmin) margin-left:auto; text-align:right; background:#e0f2fe; color:#111827; border-color:#bae6fd;
                    @else margin-right:auto; text-align:left; background:#fff; color:#111827; border-color:#e5e7eb;
                    @endif">
                    <div style="white-space:pre-wrap;">{{ $msg['body'] }}</div>
                    <div style="font-size:0.75rem; color:#9ca3af; margin-top:0.25rem; text-align:right;">{{ $msg['created_at_human'] }}</div>
                </div>
            @empty
                <div style="text-align:center; color:#9ca3af; padding:2rem 1rem;">No messages yet.</div>
            @endforelse
        </div>

        <div style="border-top:1px solid #e5e7eb; padding:1rem;">
            @if ($isClosed)
                <div style="text-align:center; padding:1rem;">
                    <div style="display:inline-block; padding:0.75rem; background:#f3f4f6; border:1px solid #e5e7eb; border-radius:0.25rem;">
                        <p style="font-size:0.875rem; color:#6b7280; margin:0 0 0.5rem;">This ticket is closed.</p>
                        <a href="{{ TicketListPage::getUrl() }}" style="display:inline-block; border:1px solid #bae6fd; background:#fff; color:#0369a1; font-size:0.75rem; font-weight:500; padding:0.375rem 0.75rem; text-decoration:none;">&larr; Back to list</a>
                    </div>
                </div>
            @else
                <div style="display:flex; align-items:flex-start; gap:0.75rem;">
                    <div style="flex:1;">
                        <textarea wire:model="replyBody" rows="2" style="display:block; width:100%; border:1px solid #d1d5db; padding:0.5rem 0.75rem; font-size:0.875rem; border-radius:0.25rem;" placeholder="Type your reply..."></textarea>
                        @error('replyBody') <p style="margin-top:0.25rem; font-size:0.75rem; color:#e11d48;">{{ $message }}</p> @enderror
                    </div>
                    <div style="display:flex; flex-direction:column; gap:0.5rem; flex-shrink:0;">
                        <button type="button" wire:click="reply" wire:loading.attr="disabled" style="border:1px solid #bae6fd; background:#fff; color:#0369a1; padding:0.5rem 1rem; font-size:0.875rem; font-weight:500; cursor:pointer;">Send</button>
                        <button type="button" wire:click="close" wire:loading.attr="disabled" style="border:1px solid #d1d5db; background:#fff; color:#4b5563; padding:0.5rem 1rem; font-size:0.875rem; font-weight:500; cursor:pointer;">Close</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
