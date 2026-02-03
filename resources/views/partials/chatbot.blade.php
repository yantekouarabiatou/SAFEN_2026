<div x-data="chatbot()" class="chatbot-widget">
    <!-- Chat Window -->
    <template x-if="isOpen">
        <div class="chatbot-window bg-white">
            <!-- Header -->
            <div class="bg-benin-green text-white d-flex align-items-center justify-content-between p-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-2"
                         style="width: 40px; height: 40px;">
                        <span style="font-size: 24px;">🕷️</span>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold">Anansi</h6>
                        <small class="opacity-75">Assistant culturel IA</small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown"
                                aria-label="Select language">
                            FR
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><button class="dropdown-item" @click="changeLanguage('fr')">Français</button></li>
                            <li><button class="dropdown-item" @click="changeLanguage('en')">English</button></li>
                        </ul>
                    </div>
                    <button class="btn btn-sm btn-outline-light" @click="isOpen = false" aria-label="Close chat">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div class="messages-container p-3 overflow-auto" style="max-height: 300px; background-color: #f8f9fa;">
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.role === 'user' ? 'text-end mb-3' : 'text-start mb-3'">
                        <div :class="msg.role === 'user'
                                    ? 'bg-benin-green text-white d-inline-block p-3 rounded-3'
                                    : 'bg-white border d-inline-block p-3 rounded-3'"
                             style="max-width: 80%;">
                            <span x-text="msg.content"></span>
                            <small class="d-block opacity-75 mt-1" x-text="msg.time"></small>
                        </div>
                    </div>
                </template>
                <div x-show="loading" class="text-center text-muted small">
                    <div class="spinner-border spinner-border-sm text-benin-green me-2" role="status"></div>
                    Anansi réfléchit...
                </div>
            </div>

            <!-- Suggestions -->
            <div class="px-3 py-2 border-top bg-white">
                <div class="d-flex flex-wrap gap-1">
                    <template x-for="(suggestion, index) in suggestions" :key="index">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill" style="font-size: 0.75rem;"
                                @click="handleSuggestion(suggestion)">
                            <span x-text="suggestion"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Input -->
            <div class="card-footer bg-white border-top p-2">
                <div class="input-group">
                    <input type="text" class="form-control border-0" placeholder="Posez votre question..."
                           x-model="input" @keydown.enter="sendMessage"
                           :disabled="loading">
                    <button class="btn btn-benin-green" @click="sendMessage" :disabled="loading">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Toggle Button -->
    <div x-data="{ isOpen: false, toggleChat() { this.isOpen = !this.isOpen } }">
    <button class="chatbot-btn" @click="toggleChat" aria-label="Open chat">
        <template x-if="!isOpen">
            <i class="bi bi-chat-dots-fill fs-4"></i>
        </template>
        <template x-if="isOpen">
            <i class="bi bi-x-lg fs-4"></i>
        </template>
    </button>
    </div>

</div>

<script>
function chatbot() {
    return {
        isOpen: false,
        messages: [
            {
                role: 'assistant',
                content: "Bonjour ! Je suis Anansi, votre guide culturel. Comment puis-je vous aider à découvrir le Bénin aujourd'hui ?",
                time: this.getCurrentTime()
            }
        ],
        input: '',
        loading: false,
        language: 'fr',
        suggestions: [
            "Trouve-moi un tailleur",
            "C'est quoi le Amiwo ?",
            "Explique le masque Guèlèdè",
            "Quels sont les meilleurs artisans à Cotonou ?",
            "Comment commander un produit ?"
        ],

        init() {
            // Charger l'historique depuis localStorage
            const savedHistory = localStorage.getItem('chatbotHistory');
            if (savedHistory) {
                this.messages = JSON.parse(savedHistory);
            }
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
        },

        getCurrentTime() {
            const now = new Date();
            return now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        },

        async sendMessage() {
            if (!this.input.trim()) return;

            // Ajouter le message de l'utilisateur
            this.messages.push({
                role: 'user',
                content: this.input,
                time: this.getCurrentTime()
            });

            const userMessage = this.input;
            this.input = '';
            this.loading = true;

            // Faire défiler vers le bas
            this.scrollToBottom();

            try {
                // Envoyer au backend Laravel
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

                // Ajouter la réponse de l'IA
                this.messages.push({
                    role: 'assistant',
                    content: data.reply,
                    time: this.getCurrentTime()
                });

                // Sauvegarder l'historique
                this.saveHistory();

            } catch (error) {
                console.error('Erreur chatbot:', error);
                this.messages.push({
                    role: 'assistant',
                    content: "Désolé, une erreur s'est produite. Veuillez réessayer.",
                    time: this.getCurrentTime()
                });
            } finally {
                this.loading = false;
                this.scrollToBottom();
            }
        },

        handleSuggestion(suggestion) {
            this.input = suggestion;
            this.sendMessage();
        },

        changeLanguage(lang) {
            this.language = lang;
            // Ici, vous pourriez recharger les suggestions dans la langue sélectionnée
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.querySelector('.messages-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        saveHistory() {
            // Garder seulement les 20 derniers messages
            const history = this.messages.slice(-20);
            localStorage.setItem('chatbotHistory', JSON.stringify(history));
        }
    }
}
</script>
