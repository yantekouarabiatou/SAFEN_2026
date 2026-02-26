<div x-data="chatbot()" class="chatbot-widget">

    {{-- ===== FENÊTRE DU CHAT ===== --}}
    <template x-if="isOpen">
        <div class="chatbot-window">

            {{-- HEADER --}}
            <div class="chatbot-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="anansi-avatar">
                        <span>🕷️</span>
                        <span class="online-dot"></span>
                    </div>
                    <div>
                        <div class="chatbot-name">Anansi</div>
                        <div class="chatbot-status">
                            <span class="status-dot"></span>
                            Assistant culturel IA
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button class="lang-btn dropdown-toggle" data-bs-toggle="dropdown">
                            <span x-text="language.toUpperCase()"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" @click="changeLanguage('fr')">🇫🇷 Français</button></li>
                            <li><button class="dropdown-item" @click="changeLanguage('en')">🇬🇧 English</button></li>
                        </ul>
                    </div>
                    <button class="close-btn" @click="isOpen = false">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            {{-- MESSAGES --}}
            <div class="messages-container" id="messagesContainer">
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="['message-row', msg.role === 'user' ? 'message-user' : 'message-bot']">

                        {{-- Avatar bot --}}
                        <template x-if="msg.role === 'assistant'">
                            <div class="bot-avatar">🕷️</div>
                        </template>

                        <div :class="['message-bubble', msg.role === 'user' ? 'bubble-user' : 'bubble-bot']">
                            <div class="message-content" x-html="formatMessage(msg.content)"></div>
                            <div class="message-time" x-text="msg.time"></div>
                        </div>
                    </div>
                </template>

                {{-- Typing indicator --}}
                <div x-show="loading" class="message-row message-bot">
                    <div class="bot-avatar">🕷️</div>
                    <div class="bubble-bot typing-bubble">
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                </div>
            </div>

            {{-- SUGGESTIONS --}}
            <div class="suggestions-bar" x-show="messages.length <= 1">
                <p class="suggestions-label">Suggestions :</p>
                <div class="d-flex flex-wrap gap-1">
                    <template x-for="(suggestion, index) in suggestions" :key="index">
                        <button class="suggestion-chip" @click="handleSuggestion(suggestion)">
                            <span x-text="suggestion"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- INPUT --}}
            <div class="chatbot-input-area">
                <div class="input-wrapper">
                    <input
                        type="text"
                        class="chat-input"
                        :placeholder="language === 'fr' ? 'Posez votre question...' : 'Ask your question...'"
                        x-model="input"
                        @keydown.enter="sendMessage"
                        :disabled="loading"
                        x-ref="chatInput"
                    >
                    <button class="send-btn" @click="sendMessage" :disabled="loading || !input.trim()">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
                <p class="powered-by">Propulsé par <strong>Anansi AI</strong> · AFRI-HERITAGE</p>
            </div>

        </div>
    </template>

    {{-- BOUTON TOGGLE --}}
    <button
        class="chatbot-toggle"
        @click="toggleChat()"
        :class="{ 'is-open': isOpen }"
        aria-label="Ouvrir le chat"
    >
        <span class="toggle-icon" x-show="!isOpen">
            <i class="bi bi-chat-dots-fill"></i>
            <span class="notif-badge" x-show="unreadCount > 0" x-text="unreadCount"></span>
        </span>
        <span class="toggle-icon" x-show="isOpen">
            <i class="bi bi-x-lg"></i>
        </span>
    </button>

</div>

{{-- ===== STYLES ===== --}}
<style>
/* Widget container */
.chatbot-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    font-family: 'Segoe UI', system-ui, sans-serif;
}

/* Fenêtre principale */
.chatbot-window {
    position: absolute;
    bottom: 72px;
    right: 0;
    width: 370px;
    height: 560px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18), 0 4px 20px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Header */
.chatbot-header {
    background: linear-gradient(135deg, #1a6b3c 0%, #2d9e5f 100%);
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.anansi-avatar {
    width: 42px;
    height: 42px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    position: relative;
    border: 2px solid rgba(255,255,255,0.4);
}

.online-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 10px;
    height: 10px;
    background: #4ade80;
    border-radius: 50%;
    border: 2px solid white;
}

.chatbot-name {
    color: white;
    font-weight: 700;
    font-size: 15px;
    line-height: 1.2;
}

.chatbot-status {
    color: rgba(255,255,255,0.8);
    font-size: 11px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.status-dot {
    width: 6px;
    height: 6px;
    background: #4ade80;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}

.lang-btn {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.lang-btn:hover { background: rgba(255,255,255,0.25); }

.close-btn {
    background: rgba(255,255,255,0.15);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
    font-size: 13px;
}
.close-btn:hover { background: rgba(255,255,255,0.25); }

/* Zone messages */
.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 16px 14px;
    background: #f5f6f8;
    display: flex;
    flex-direction: column;
    gap: 10px;
    scroll-behavior: smooth;
}

.messages-container::-webkit-scrollbar { width: 4px; }
.messages-container::-webkit-scrollbar-track { background: transparent; }
.messages-container::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

/* Lignes de message */
.message-row {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    animation: fadeIn 0.25s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}

.message-user { flex-direction: row-reverse; }

.bot-avatar {
    width: 28px;
    height: 28px;
    background: #1a6b3c;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

/* Bulles */
.message-bubble {
    max-width: 78%;
    border-radius: 16px;
    padding: 10px 14px;
    position: relative;
}

.bubble-user {
    background: linear-gradient(135deg, #1a6b3c, #2d9e5f);
    color: white;
    border-bottom-right-radius: 4px;
}

.bubble-bot {
    background: white;
    color: #1f2937;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.message-content {
    font-size: 13.5px;
    line-height: 1.5;
    word-break: break-word;
}

.message-content strong { font-weight: 600; }
.message-content a { color: #2d9e5f; text-decoration: underline; }
.bubble-user .message-content a { color: rgba(255,255,255,0.9); }

.message-time {
    font-size: 10px;
    opacity: 0.55;
    margin-top: 4px;
    text-align: right;
}

/* Typing indicator */
.typing-bubble {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 12px 16px;
    min-width: 60px;
}

.dot {
    width: 7px;
    height: 7px;
    background: #9ca3af;
    border-radius: 50%;
    animation: bounce 1.2s infinite;
}
.dot:nth-child(2) { animation-delay: 0.2s; }
.dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes bounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-6px); }
}

/* Suggestions */
.suggestions-bar {
    padding: 10px 14px 8px;
    background: white;
    border-top: 1px solid #f0f0f0;
    flex-shrink: 0;
}

.suggestions-label {
    font-size: 10px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    font-weight: 600;
}

.suggestion-chip {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 11.5px;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.suggestion-chip:hover {
    background: #dcfce7;
    border-color: #86efac;
    transform: translateY(-1px);
}

/* Zone de saisie */
.chatbot-input-area {
    padding: 12px 14px 10px;
    background: white;
    border-top: 1px solid #f0f0f0;
    flex-shrink: 0;
}

.input-wrapper {
    display: flex;
    align-items: center;
    background: #f5f6f8;
    border-radius: 14px;
    padding: 6px 6px 6px 14px;
    border: 1.5px solid #e5e7eb;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.input-wrapper:focus-within {
    border-color: #2d9e5f;
    box-shadow: 0 0 0 3px rgba(45,158,95,0.12);
}

.chat-input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-size: 13.5px;
    color: #1f2937;
    padding: 4px 0;
    min-width: 0;
}

.chat-input::placeholder { color: #9ca3af; }
.chat-input:disabled { opacity: 0.6; cursor: not-allowed; }

.send-btn {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #1a6b3c, #2d9e5f);
    border: none;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.2s;
    flex-shrink: 0;
}

.send-btn:hover:not(:disabled) {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(26,107,60,0.35);
}

.send-btn:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    transform: none;
}

.powered-by {
    text-align: center;
    font-size: 10px;
    color: #c4c4c4;
    margin: 6px 0 0;
}

/* Bouton toggle */
.chatbot-toggle {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #1a6b3c, #2d9e5f);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    box-shadow: 0 4px 20px rgba(26,107,60,0.4);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
}

.chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(26,107,60,0.5);
}

.chatbot-toggle.is-open {
    background: linear-gradient(135deg, #374151, #4b5563);
}

.toggle-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.notif-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #ef4444;
    color: white;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    font-size: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

/* Responsive mobile */
@media (max-width: 480px) {
    .chatbot-window {
        width: calc(100vw - 32px);
        height: 70vh;
        bottom: 68px;
        right: 0;
    }
    .chatbot-widget {
        bottom: 16px;
        right: 16px;
    }
}
</style>

{{-- ===== SCRIPT ===== --}}
<script>
function chatbot() {
    return {
        isOpen: false,
        messages: [],
        input: '',
        loading: false,
        language: 'fr',
        unreadCount: 0,
        suggestions: [
            "Trouve-moi un tailleur",
            "C'est quoi le Amiwo ?",
            "Explique le masque Guèlèdè",
            "Artisans à Cotonou ?",
            "Commander un produit ?"
        ],

        init() {
            // Message de bienvenue
            this.messages.push({
                role: 'assistant',
                content: "Bonjour ! Je suis **Anansi** 🕷️, votre guide culturel béninois.\n\nComment puis-je vous aider à découvrir le Bénin aujourd'hui ?",
                time: this.getCurrentTime()
            });

            // Charger l'historique
            try {
                const saved = localStorage.getItem('anansiHistory');
                if (saved) {
                    const parsed = JSON.parse(saved);
                    if (parsed.length > 0) this.messages = parsed;
                }
            } catch(e) {}
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            this.unreadCount = 0;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.scrollToBottom();
                    if (this.$refs.chatInput) this.$refs.chatInput.focus();
                });
            }
        },

        getCurrentTime() {
            return new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        },

        // Convertit le markdown basique en HTML
        formatMessage(text) {
            if (!text) return '';
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\[([^\]]+)\]\((https?:\/\/[^\)]+)\)/g, '<a href="$2" target="_blank">$1</a>')
                .replace(/\[([^\]]+)\]\(([^\)]+)\)/g, '<a href="$2">$1</a>')
                .replace(/\n/g, '<br>');
        },

        async sendMessage() {
            if (!this.input.trim() || this.loading) return;

            const userMessage = this.input.trim();
            this.messages.push({
                role: 'user',
                content: userMessage,
                time: this.getCurrentTime()
            });

            this.input = '';
            this.loading = true;
            this.$nextTick(() => this.scrollToBottom());

            try {
                const response = await fetch('{{ route("chatbot.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message: userMessage,
                        language: this.language
                    })
                });

                const data = await response.json();

                this.messages.push({
                    role: 'assistant',
                    content: data.reply || "Désolé, je n'ai pas pu répondre.",
                    time: this.getCurrentTime()
                });

                if (!this.isOpen) this.unreadCount++;
                this.saveHistory();

            } catch (error) {
                console.error('Chatbot error:', error);
                this.messages.push({
                    role: 'assistant',
                    content: "Désolé, une erreur s'est produite. Veuillez réessayer.",
                    time: this.getCurrentTime()
                });
            } finally {
                this.loading = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        handleSuggestion(suggestion) {
            this.input = suggestion;
            this.sendMessage();
        },

        changeLanguage(lang) {
            this.language = lang;
            const greetings = {
                fr: "Langue changée en **Français** 🇫🇷",
                en: "Language changed to **English** 🇬🇧"
            };
            this.messages.push({
                role: 'assistant',
                content: greetings[lang],
                time: this.getCurrentTime()
            });
        },

        scrollToBottom() {
            const el = document.getElementById('messagesContainer');
            if (el) el.scrollTop = el.scrollHeight;
        },

        saveHistory() {
            try {
                localStorage.setItem('anansiHistory', JSON.stringify(this.messages.slice(-20)));
            } catch(e) {}
        }
    }
}

function openChat() {
    const el = document.querySelector('.chatbot-widget');
    if (el && el._x_dataStack) {
        const instance = el._x_dataStack[0];
        if (instance && !instance.isOpen) instance.toggleChat();
    }
}
</script>