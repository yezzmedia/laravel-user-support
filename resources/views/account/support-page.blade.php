<div class="space-y-6">
    @php $tickets = $tickets ?? []; @endphp

    {{-- Contact Form --}}
    <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-4 bg-sky-400"></span>
                <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact Us</h2>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Have a question? Send us a message.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                    <select wire:model="data.subject" class="mt-1 block w-full border border-gray-300 px-3 py-2 text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="">Select…</option>
                        <option value="technical">Technical issue</option>
                        <option value="billing">Billing question</option>
                        <option value="account">Account issue</option>
                        <option value="other">Other</option>
                    </select>
                    @error('data.subject') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                    <textarea wire:model="data.message" rows="4" class="mt-1 block w-full border border-gray-300 px-3 py-2 text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white" placeholder="Describe your issue..."></textarea>
                    @error('data.message') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                <button type="button" wire:click="sendContact" wire:loading.attr="disabled" class="border border-sky-300 bg-white px-4 py-2 text-sm font-medium text-sky-700 hover:bg-sky-50 dark:border-sky-700 dark:bg-gray-800 dark:text-sky-300 dark:hover:bg-sky-900/20 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Send message
                </button>
            </div>
        </div>
    </div>

    {{-- Help Center --}}
    <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 mb-6" x-data="{ activeCategory: null }">
        <div class="p-4 sm:p-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-4 bg-emerald-400"></span>
                <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Help Center</h2>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 leading-relaxed mb-4">Frequently asked questions and helpful tips about your account.</p>

            @php
                $helpCategories = [
                    'account' => [
                        'title' => 'Account Management',
                        'icon' => 'user',
                        'items' => [
                            ['q' => 'How do I change my profile information?', 'a' => 'Go to your Profile page and update your personal details. Changes take effect immediately after saving.'],
                            ['q' => 'How do I change my email address?', 'a' => 'Email changes require verification. Please contact support if you need to update your email.'],
                            ['q' => 'How do I delete my account?', 'a' => 'Visit the Privacy page and use the \'Request Account Deletion\' option. This action is permanent.'],
                        ],
                    ],
                    'security' => [
                        'title' => 'Security & Privacy',
                        'icon' => 'shield',
                        'items' => [
                            ['q' => 'How do I enable two-factor authentication?', 'a' => 'Go to your Security page and follow the setup instructions for two-factor authentication. We recommend using an authenticator app.'],
                            ['q' => 'What happens if I lose my recovery codes?', 'a' => 'You can regenerate recovery codes on the Security page. Old codes will stop working immediately.'],
                            ['q' => 'How do I see which devices are logged in?', 'a' => 'The Devices page shows all active sessions with location, browser, and platform information.'],
                        ],
                    ],
                    'integrations' => [
                        'title' => 'Connected Accounts & API',
                        'icon' => 'link',
                        'items' => [
                            ['q' => 'How do I connect my social accounts?', 'a' => 'Visit the Connected Accounts page to link your Google, GitHub, or other supported provider accounts.'],
                            ['q' => 'How do I create an API token?', 'a' => 'Go to the API Tokens page and click \'Create Token\'. Give your token a descriptive name and copy it immediately.'],
                            ['q' => 'What happens when I revoke an API token?', 'a' => 'Revoked tokens stop working immediately. Any application using that token will lose access.'],
                        ],
                    ],
                    'appearance' => [
                        'title' => 'Appearance & Settings',
                        'icon' => 'palette',
                        'items' => [
                            ['q' => 'How do I switch to dark mode?', 'a' => 'Go to the Appearance page and select Dark, Light, or System theme. The change applies instantly.'],
                            ['q' => 'How do I change my language?', 'a' => 'On the Profile page, select your preferred language under Language & Region. The interface updates immediately.'],
                            ['q' => 'How do I manage notification preferences?', 'a' => 'The Notifications page lets you choose which events trigger email or in-app notifications.'],
                        ],
                    ],
                    'support' => [
                        'title' => 'Support & Contact',
                        'icon' => 'help-circle',
                        'items' => [
                            ['q' => 'How do I contact support?', 'a' => 'Use the contact form above to send us a message. We typically respond within 24 hours.'],
                            ['q' => 'How do I track my support tickets?', 'a' => 'All your submitted tickets appear in the \'My Tickets\' section below. Click a ticket to see the full conversation.'],
                            ['q' => 'Where can I find legal information?', 'a' => 'Our Impressum, Privacy Policy, and Terms of Service are linked in the footer of every page.'],
                        ],
                    ],
                ];
            @endphp

            <div class="space-y-1">
                @foreach ($helpCategories as $catKey => $cat)
                    <div class="border-l-2 border-transparent hover:border-emerald-300 dark:hover:border-emerald-700 transition">
                        <button type="button"
                            @click="activeCategory = activeCategory === '{{ $catKey }}' ? null : '{{ $catKey }}'"
                            class="flex w-full items-center gap-3 px-3 py-2.5 text-left">
                            <span class="flex-shrink-0 w-8 h-8 bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center">
                                <x-account::icon :name="$cat['icon']" class="h-4 w-4 text-emerald-500" />
                            </span>
                            <span class="flex-1 text-sm font-medium text-gray-900 dark:text-white">{{ $cat['title'] }}</span>
                            <x-account::icon name="chevron-down"
                                ::class="activeCategory === '{{ $catKey }}' ? 'rotate-180' : ''"
                                class="h-4 w-4 text-gray-400 transition-transform" />
                        </button>
                        <div x-show="activeCategory === '{{ $catKey }}'" x-cloak class="border-t border-gray-100 dark:border-gray-800 ml-11">
                            @foreach ($cat['items'] as $item)
                                <div x-data="{ faqOpen: false }" class="border-l-2 border-transparent hover:border-gray-300 dark:hover:border-gray-700">
                                    <button type="button" @click="faqOpen = !faqOpen" class="flex w-full items-center gap-2 px-3 py-2 text-left text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <span class="text-xs text-gray-400">Q:</span>
                                        <span class="flex-1">{{ $item['q'] }}</span>
                                        <x-account::icon name="chevron-down"
                                            ::class="faqOpen ? 'rotate-180' : ''"
                                            class="h-3.5 w-3.5 text-gray-400 transition-transform shrink-0" />
                                    </button>
                                    <div x-show="faqOpen" x-cloak class="px-8 pb-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $item['a'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- My Tickets --}}
    <div class="border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 mb-6">
        <div class="divide-y divide-gray-200 dark:divide-gray-800">
            <div class="p-4 sm:p-6">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-4 bg-sky-400"></span>
                    <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('user-support::user-support.my_tickets') }}</h2>
                </div>
            </div>
            @if (count($tickets) === 0)
                <div class="p-4 sm:p-6"><p class="text-sm text-gray-500 dark:text-gray-400">{{ __('user-support::user-support.my_tickets_empty') }}</p></div>
            @else
                @foreach ($tickets as $ticket)
                    @php
                        $isExpanded = ($this->expandedTicketId ?? null) === $ticket['id'];
                        $statusLabel = match ($ticket['status']) { 'open' => __('user-support::user-support.status_open'), 'replied' => __('user-support::user-support.status_replied'), 'closed' => __('user-support::user-support.status_closed'), default => $ticket['status'] };
                        $statusClass = match ($ticket['status']) { 'open' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'replied' => 'bg-sky-50 text-sky-700 dark:bg-sky-900/30 dark:text-sky-400', 'closed' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400', default => 'bg-gray-100 text-gray-600' };
                    @endphp
                    <div class="border-l-2 border-transparent hover:border-sky-300 dark:hover:border-sky-700 transition {{ $isExpanded ? 'border-sky-400 dark:border-sky-600 bg-sky-50/30 dark:bg-sky-900/10' : '' }}">
                        <button type="button" wire:click="toggleTicket({{ $ticket['id'] }})" class="flex w-full items-center justify-between gap-3 px-4 py-3 sm:px-6 sm:py-4 text-left">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-mono text-gray-400 dark:text-gray-500">#{{ $ticket['ticket_number'] }}</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $ticket['subject'] }}</span>
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        @if ($ticket['reply_count'] > 0) {{ $ticket['reply_count'] }} replies · {{ $ticket['last_reply_at'] }} @else No replies yet @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs font-medium px-2 py-0.5 {{ $statusClass }}">{{ $statusLabel }}</span>
                                <x-account::icon name="chevron-down" class="h-4 w-4 text-gray-400 transition-transform {{ $isExpanded ? 'rotate-180' : '' }}" />
                            </div>
                        </button>
                        @if ($isExpanded)
                            <div class="border-t border-gray-100 dark:border-gray-800">
                                <div class="p-4 sm:p-6">
                                    @if (count($this->expandedConversation ?? []) > 0)
                                        <div class="max-h-96 overflow-y-auto space-y-3 py-3">
                                            @foreach ($this->expandedConversation as $msg)
                                                @php $isMine = $msg['sender_type'] === 'user'; @endphp
                                                <div class="flex flex-col {{ $isMine ? 'ml-auto text-right' : 'mr-auto text-left' }} max-w-[80%] {{ $isMine ? 'bg-sky-100 dark:bg-sky-900/30 text-gray-900 dark:text-white border-sky-200 dark:border-sky-800' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-gray-200 dark:border-gray-700' }} border px-3 py-2 text-sm">
                                                    <div class="whitespace-pre-wrap">{{ $msg['body'] }}</div>
                                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1 text-right">{{ $msg['created_at_human'] }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center text-sm text-gray-400 dark:text-gray-500 py-4">No messages yet.</div>
                                    @endif

                                    @if ($ticket['status'] === 'closed')
                                        <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-center">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('user-support::user-support.ticket_closed') }}</p>
                                        </div>
                                    @else
                                        <div class="mt-4 flex items-center gap-2">
                                            <button type="button" wire:click="closeTicket({{ $ticket['id'] }})" wire:loading.attr="disabled" class="border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700">{{ __('user-support::user-support.close_ticket') }}</button>
                                        </div>
                                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                                            <textarea wire:model="replyBody" rows="2" class="block w-full border border-gray-300 px-3 py-2 text-sm focus:border-sky-500 focus:ring-1 focus:ring-sky-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white" placeholder="Type your reply..."></textarea>
                                            @error('replyBody') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                                            <div class="mt-2">
                                                <button type="button" wire:click="replyToTicket({{ $ticket['id'] }})" wire:loading.attr="disabled" class="border border-sky-300 bg-white px-4 py-2 text-sm font-medium text-sky-700 hover:bg-sky-50 dark:border-sky-700 dark:bg-gray-800 dark:text-sky-300 dark:hover:bg-sky-900/20 transition disabled:opacity-50 disabled:cursor-not-allowed">Send Reply</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
