<div x-data="chatbot()" class="chatbot-widget">

    {{-- ===== FENÊTRE DU CHAT ===== --}}
    <template x-if="isOpen">
        <div class="chatbot-window">

            {{-- HEADER --}}
            <div class="chatbot-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="anansi-avatar">
                        <svg width="24" height="24" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="22" cy="22" r="20" fill="rgba(255,255,255,0.15)"/>
                            <circle cx="22" cy="22" r="20" stroke="rgba(252,209,22,0.6)" stroke-width="1.5"/>
                            <rect x="9" y="13" width="26" height="5" rx="2.5" fill="white"/>
                            <rect x="17.5" y="13" width="9" height="19" rx="2.5" fill="white"/>
                            <circle cx="5.5" cy="22" r="2" fill="#FCD116"/>
                            <circle cx="38.5" cy="22" r="2" fill="#FCD116"/>
                            <circle cx="22" cy="5" r="1.7" fill="#E8112D"/>
                        </svg>
                        <span class="online-dot"></span>
                    </div>
                    <div>
                        <div class="chatbot-name">Anansi</div>
                        <div class="chatbot-status">
                            <span class="status-dot"></span>
                            <span x-text="mode === 'artisan' ? 'Mode rédaction artisan' : 'Assistant culturel IA'"></span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1">
                    {{-- Sélecteur de langue --}}
                    <div class="dropdown">
                        <button class="lang-btn dropdown-toggle" data-bs-toggle="dropdown">
                            <span x-text="langFlag()"></span>
                            <span x-text="language.toUpperCase()" class="ms-1"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end py-1" style="min-width:150px;font-size:.8rem;">
                            <li><button class="dropdown-item py-1" @click="changeLanguage('fr')">🇫🇷 Français</button></li>
                            <li><button class="dropdown-item py-1" @click="changeLanguage('en')">🇬🇧 English</button></li>
                            <li><button class="dropdown-item py-1" @click="changeLanguage('fon')">🇧🇯 Fon</button></li>
                            <li><button class="dropdown-item py-1" @click="changeLanguage('yoruba')">🌍 Yoruba</button></li>
                        </ul>
                    </div>
                    {{-- Mode artisan (auth seulement) --}}
                    @auth
                    <button class="mode-btn" :class="{ 'mode-active': mode === 'artisan' }"
                            @click="toggleMode()" title="Mode rédaction artisan">
                        ✍️
                    </button>
                    @endauth
                    <button class="close-btn" @click="isOpen = false">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            {{-- MODE ARTISAN : panneau d'actions rapides --}}
            <div x-show="mode === 'artisan'" class="artisan-panel" style="display:none;">
                <p class="artisan-panel-title">✨ Que veux-tu que je t'écrive ?</p>
                <div class="artisan-actions">
                    <button class="artisan-action-btn" @click="startAction('product')">
                        <span class="action-icon">🏺</span>
                        <span>Décrire un produit</span>
                    </button>
                    <button class="artisan-action-btn" @click="startAction('bio')">
                        <span class="action-icon">👤</span>
                        <span>Rédiger ma bio</span>
                    </button>
                    <button class="artisan-action-btn" @click="startAction('story')">
                        <span class="action-icon">📖</span>
                        <span>Histoire culturelle</span>
                    </button>
                    <button class="artisan-action-btn" @click="startAction('translate')">
                        <span class="action-icon">🌍</span>
                        <span>Traduire en Fon/Yoruba</span>
                    </button>
                </div>

                {{-- Formulaire contextuel --}}
                <div x-show="activeAction" class="action-form" style="display:none;">
                    {{-- Décrire un produit --}}
                    <template x-if="activeAction === 'product'">
                        <div>
                            <div class="form-group">
                                <input x-model="productForm.name" type="text" class="action-input" placeholder="Nom du produit (ex: Masque Guèlèdè)">
                            </div>
                            <div class="form-group">
                                <input x-model="productForm.category" type="text" class="action-input" placeholder="Catégorie (ex: Masque, Tissu, Bijou...)">
                            </div>
                            <div class="form-group">
                                <input x-model="productForm.materials" type="text" class="action-input" placeholder="Matériaux (ex: bois de fromager, peinture naturelle)">
                            </div>
                            <div class="form-group">
                                <input x-model="productForm.ethnic_origin" type="text" class="action-input" placeholder="Origine ethnique (ex: Fon, Yoruba, Bariba...)">
                            </div>
                            <button class="generate-btn" @click="generate('product')" :disabled="loading">
                                <span x-show="!loading">🕷️ Générer la description</span>
                                <span x-show="loading">Anansi réfléchit...</span>
                            </button>
                        </div>
                    </template>

                    {{-- Rédiger une bio --}}
                    <template x-if="activeAction === 'bio'">
                        <div>
                            <div class="form-group">
                                <input x-model="bioForm.name" type="text" class="action-input" placeholder="Votre nom">
                            </div>
                            <div class="form-group">
                                <input x-model="bioForm.craft" type="text" class="action-input" placeholder="Votre métier (ex: Sculpteur sur bois)">
                            </div>
                            <div class="form-group">
                                <input x-model="bioForm.city" type="text" class="action-input" placeholder="Votre ville (ex: Cotonou, Abomey...)">
                            </div>
                            <div class="form-group">
                                <input x-model="bioForm.experience" type="text" class="action-input" placeholder="Années d'expérience (optionnel)">
                            </div>
                            <div class="form-group">
                                <input x-model="bioForm.specialties" type="text" class="action-input" placeholder="Spécialités (optionnel)">
                            </div>
                            <button class="generate-btn" @click="generate('bio')" :disabled="loading">
                                <span x-show="!loading">🕷️ Rédiger ma biographie</span>
                                <span x-show="loading">Anansi rédige...</span>
                            </button>
                        </div>
                    </template>

                    {{-- Histoire culturelle --}}
                    <template x-if="activeAction === 'story'">
                        <div>
                            <div class="form-group">
                                <input x-model="storySubject" type="text" class="action-input" placeholder="Sujet (ex: Masque Guèlèdè, Tissu Kente, Vaudou...)">
                            </div>
                            <button class="generate-btn" @click="generate('story')" :disabled="loading">
                                <span x-show="!loading">🕷️ Raconter l'histoire</span>
                                <span x-show="loading">Anansi conte...</span>
                            </button>
                        </div>
                    </template>

                    {{-- Traduction --}}
                    <template x-if="activeAction === 'translate'">
                        <div>
                            <div class="form-group">
                                <textarea x-model="translateText" class="action-input" rows="3"
                                          placeholder="Texte à traduire..."></textarea>
                            </div>
                            <div class="form-group">
                                <select x-model="translateTarget" class="action-input">
                                    <option value="fon">Vers le Fon 🇧🇯</option>
                                    <option value="yoruba">Vers le Yoruba 🌍</option>
                                    <option value="en">Vers l'anglais 🇬🇧</option>
                                    <option value="fr">Vers le français 🇫🇷</option>
                                </select>
                            </div>
                            <button class="generate-btn" @click="translateAction()" :disabled="loading">
                                <span x-show="!loading">🕷️ Traduire</span>
                                <span x-show="loading">Anansi traduit...</span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- MESSAGES --}}
            <div class="messages-container" id="messagesContainer" x-show="mode === 'chat'">
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="['message-row', msg.role === 'user' ? 'message-user' : 'message-bot']">
                        <template x-if="msg.role === 'assistant'">
                            <div class="bot-avatar">🕷️</div>
                        </template>
                        <div :class="['message-bubble', msg.role === 'user' ? 'bubble-user' : 'bubble-bot']">
                            <div class="message-content" x-html="formatMessage(msg.content)"></div>
                            <div class="message-actions" x-show="msg.role === 'assistant' && msg.content.length > 80">
                                <button class="msg-action-btn" @click="copyText(msg.content)" title="Copier">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <div class="message-time" x-text="msg.time"></div>
                        </div>
                    </div>
                </template>

                {{-- Typing indicator --}}
                <div x-show="loading" class="message-row message-bot">
                    <div class="bot-avatar">🕷️</div>
                    <div class="bubble-bot typing-bubble">
                        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
                    </div>
                </div>
            </div>

            {{-- Résultat génération (mode artisan) --}}
            <div class="generation-result" x-show="mode === 'artisan' && generatedText" style="display:none;">
                <div class="generation-result-header">
                    <span>✨ Résultat d'Anansi</span>
                    <div class="d-flex gap-1">
                        <button class="result-btn" @click="copyGenerated()" title="Copier">
                            <i class="bi bi-clipboard"></i> Copier
                        </button>
                        <button class="result-btn result-btn-secondary" @click="generatedText = ''" title="Effacer">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
                <div class="generation-result-body" x-html="formatMessage(generatedText)"></div>
                <div x-show="copied" class="copy-toast">✅ Copié !</div>
            </div>

            {{-- Loader génération --}}
            <div x-show="mode === 'artisan' && loading && !generatedText" class="generation-loader" style="display:none;">
                <div class="spider-web-loader">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" class="spinning-web">
                        <circle cx="24" cy="24" r="22" stroke="#008751" stroke-width="1.5" stroke-dasharray="4 3" opacity=".4"/>
                        <circle cx="24" cy="24" r="14" stroke="#FCD116" stroke-width="1.5" stroke-dasharray="3 3" opacity=".5"/>
                        <circle cx="24" cy="24" r="6" stroke="#E8112D" stroke-width="1.5" opacity=".6"/>
                        <line x1="24" y1="2" x2="24" y2="46" stroke="#008751" stroke-width="1" opacity=".3"/>
                        <line x1="2" y1="24" x2="46" y2="24" stroke="#008751" stroke-width="1" opacity=".3"/>
                        <line x1="7" y1="7" x2="41" y2="41" stroke="#008751" stroke-width="1" opacity=".2"/>
                        <line x1="41" y1="7" x2="7" y2="41" stroke="#008751" stroke-width="1" opacity=".2"/>
                        <text x="24" y="28" text-anchor="middle" font-size="14">🕷️</text>
                    </svg>
                    <p style="font-size:.75rem;color:#6b7280;margin-top:8px;">Anansi tisse ta réponse...</p>
                </div>
            </div>

            {{-- SUGGESTIONS (mode chat, début) --}}
            <div class="suggestions-bar" x-show="mode === 'chat' && messages.length <= 1">
                <p class="suggestions-label">Explorer :</p>
                <div class="d-flex flex-wrap gap-1">
                    <template x-for="(s, i) in suggestions" :key="i">
                        <button class="suggestion-chip" @click="handleSuggestion(s.text)">
                            <span x-text="s.emoji"></span>
                            <span x-text="s.text"></span>
                        </button>
                    </template>
                </div>
            </div>

            {{-- INPUT (mode chat) --}}
            <div class="chatbot-input-area" x-show="mode === 'chat'">
                <div class="input-wrapper">
                    <input type="text" class="chat-input"
                           :placeholder="inputPlaceholder()"
                           x-model="input"
                           @keydown.enter="sendMessage"
                           :disabled="loading"
                           x-ref="chatInput">
                    <button class="send-btn" @click="sendMessage" :disabled="loading || !input.trim()">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
                <p class="powered-by">
                    🕷️ <strong>Anansi AI</strong> · TOTCHÉMÈGNON
                    <span class="lang-tag" x-text="langName()"></span>
                </p>
            </div>

        </div>
    </template>

    {{-- BOUTON TOGGLE --}}
    <button class="chatbot-toggle" @click="toggleChat()"
            :class="{ 'is-open': isOpen }" aria-label="Ouvrir Anansi">
        <span class="toggle-icon" x-show="!isOpen">
            <svg width="26" height="26" viewBox="0 0 44 44" fill="none">
                <rect x="9" y="13" width="26" height="5" rx="2.5" fill="white"/>
                <rect x="17.5" y="13" width="9" height="19" rx="2.5" fill="white"/>
                <circle cx="5.5" cy="22" r="2.5" fill="#FCD116"/>
                <circle cx="38.5" cy="22" r="2.5" fill="#FCD116"/>
                <circle cx="22" cy="5" r="2" fill="#E8112D"/>
            </svg>
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
    width: 380px;
    max-height: 620px;
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

/* Header — dégradé Bénin */
.chatbot-header {
    background: linear-gradient(135deg, #005c38 0%, #008751 60%, #006b42 100%);
    padding: 12px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
}

/* Motif Adinkra discret en fond */
.chatbot-header::after {
    content: '✦ ✦ ✦ ✦ ✦';
    position: absolute;
    bottom: -4px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 8px;
    color: rgba(252,209,22,.25);
    letter-spacing: 8px;
    pointer-events: none;
}

.anansi-avatar {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    border: 2px solid rgba(252,209,22,0.5);
    flex-shrink: 0;
}

.online-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 9px;
    height: 9px;
    background: #4ade80;
    border-radius: 50%;
    border: 2px solid white;
}

.chatbot-name {
    color: white;
    font-weight: 700;
    font-size: 14px;
    line-height: 1.2;
    font-family: 'Montserrat', sans-serif;
    letter-spacing: .3px;
}

.chatbot-status {
    color: rgba(255,255,255,0.75);
    font-size: 10.5px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.status-dot {
    width: 5px;
    height: 5px;
    background: #4ade80;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }

.lang-btn {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.25);
    color: white;
    border-radius: 8px;
    padding: 3px 8px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s;
}
.lang-btn:hover { background: rgba(255,255,255,0.22); }
.lang-btn::after { color: rgba(255,255,255,.6) !important; }

.mode-btn {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 8px;
    width: 30px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 14px;
    transition: all .2s;
}
.mode-btn:hover, .mode-btn.mode-active {
    background: rgba(252,209,22,.25);
    border-color: rgba(252,209,22,.5);
}

.close-btn {
    background: rgba(255,255,255,0.12);
    border: none;
    color: white;
    width: 30px;
    height: 28px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: background .2s;
}
.close-btn:hover { background: rgba(232,17,45,.4); }

/* ── MODE ARTISAN ── */
.artisan-panel {
    background: linear-gradient(180deg, #f0fdf4 0%, #fff 100%);
    padding: 12px 14px 10px;
    border-bottom: 1px solid #dcfce7;
    flex-shrink: 0;
}

.artisan-panel-title {
    font-size: 11px;
    font-weight: 700;
    color: #166534;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 8px;
}

.artisan-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
}

.artisan-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 8px 6px;
    background: white;
    border: 1.5px solid #bbf7d0;
    border-radius: 10px;
    cursor: pointer;
    font-size: 11px;
    font-weight: 600;
    color: #166534;
    transition: all .2s;
}
.artisan-action-btn:hover {
    background: #dcfce7;
    border-color: #4ade80;
    transform: translateY(-1px);
}

.action-icon { font-size: 20px; line-height: 1; }

.action-form {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #bbf7d0;
}

.action-input {
    width: 100%;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 12px;
    outline: none;
    color: #1f2937;
    background: white;
    transition: border-color .2s;
    resize: vertical;
}
.action-input:focus { border-color: #008751; box-shadow: 0 0 0 3px rgba(0,135,81,.1); }

.form-group { margin-bottom: 6px; }

.generate-btn {
    width: 100%;
    background: linear-gradient(135deg, #005c38, #008751);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 8px 12px;
    font-size: 12.5px;
    font-weight: 700;
    cursor: pointer;
    margin-top: 4px;
    transition: all .2s;
}
.generate-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(0,135,81,.35);
}
.generate-btn:disabled { opacity: .6; cursor: not-allowed; }

/* Résultat génération */
.generation-result {
    flex: 1;
    overflow-y: auto;
    padding: 12px 14px;
    background: #f0fdf4;
    border-top: 1px solid #dcfce7;
}

.generation-result-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 700;
    color: #166534;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.generation-result-body {
    font-size: 13px;
    line-height: 1.65;
    color: #1f2937;
    background: white;
    border-radius: 10px;
    padding: 12px;
    border: 1px solid #bbf7d0;
    white-space: pre-wrap;
}

.result-btn {
    background: #008751;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 3px 8px;
    font-size: 11px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 3px;
    transition: background .2s;
}
.result-btn:hover { background: #005c38; }
.result-btn-secondary { background: #6b7280; }
.result-btn-secondary:hover { background: #4b5563; }

.copy-toast {
    text-align: center;
    font-size: 11px;
    color: #166534;
    font-weight: 600;
    margin-top: 6px;
    animation: fadeIn .3s ease;
}

/* Loader toile d'araignée */
.generation-loader {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: #f0fdf4;
}

.spider-web-loader {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.spinning-web {
    animation: spin 3s linear infinite;
}

@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

/* Zone messages */
.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 14px 12px;
    background: #f7f8fa;
    display: flex;
    flex-direction: column;
    gap: 10px;
    scroll-behavior: smooth;
}

.messages-container::-webkit-scrollbar { width: 3px; }
.messages-container::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }

.message-row {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    animation: fadeIn 0.25s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}

.message-user { flex-direction: row-reverse; }

.bot-avatar {
    width: 26px;
    height: 26px;
    background: linear-gradient(135deg, #005c38, #008751);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}

.message-bubble {
    max-width: 80%;
    border-radius: 16px;
    padding: 9px 13px;
    position: relative;
}

.bubble-user {
    background: linear-gradient(135deg, #005c38, #008751);
    color: white;
    border-bottom-right-radius: 4px;
}

.bubble-bot {
    background: white;
    color: #1f2937;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.07);
}

.message-content {
    font-size: 13px;
    line-height: 1.55;
    word-break: break-word;
}

.message-content strong { font-weight: 600; }
.message-content em { font-style: italic; color: #6b7280; }
.bubble-user .message-content em { color: rgba(255,255,255,0.8); }
.message-content a { color: #008751; text-decoration: underline; }
.bubble-user .message-content a { color: rgba(255,255,255,0.9); }

.message-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 4px;
}

.msg-action-btn {
    background: none;
    border: none;
    color: #9ca3af;
    font-size: 11px;
    cursor: pointer;
    padding: 2px 4px;
    border-radius: 4px;
    transition: color .2s;
}
.msg-action-btn:hover { color: #008751; }

.message-time {
    font-size: 9.5px;
    opacity: .5;
    margin-top: 3px;
    text-align: right;
}

/* Typing indicator */
.typing-bubble {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 12px 16px;
    min-width: 56px;
}

.dot {
    width: 6px;
    height: 6px;
    background: #9ca3af;
    border-radius: 50%;
    animation: bounce 1.2s infinite;
}
.dot:nth-child(2) { animation-delay: .2s; }
.dot:nth-child(3) { animation-delay: .4s; }

@keyframes bounce { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-6px); } }

/* Suggestions */
.suggestions-bar {
    padding: 8px 12px 6px;
    background: white;
    border-top: 1px solid #f0f0f0;
    flex-shrink: 0;
}

.suggestions-label {
    font-size: 9.5px;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 5px;
    font-weight: 700;
}

.suggestion-chip {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 11px;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.suggestion-chip:hover {
    background: #dcfce7;
    border-color: #86efac;
    transform: translateY(-1px);
}

/* Zone de saisie */
.chatbot-input-area {
    padding: 10px 12px 8px;
    background: white;
    border-top: 1px solid #f0f0f0;
    flex-shrink: 0;
}

.input-wrapper {
    display: flex;
    align-items: center;
    background: #f5f6f8;
    border-radius: 14px;
    padding: 5px 5px 5px 12px;
    border: 1.5px solid #e5e7eb;
    transition: border-color .2s, box-shadow .2s;
}

.input-wrapper:focus-within {
    border-color: #008751;
    box-shadow: 0 0 0 3px rgba(0,135,81,.1);
}

.chat-input {
    flex: 1;
    border: none;
    background: transparent;
    outline: none;
    font-size: 13px;
    color: #1f2937;
    padding: 4px 0;
    min-width: 0;
}

.chat-input::placeholder { color: #9ca3af; }

.send-btn {
    width: 34px;
    height: 34px;
    background: linear-gradient(135deg, #005c38, #008751);
    border: none;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    transition: all .2s;
    flex-shrink: 0;
}

.send-btn:hover:not(:disabled) { transform: scale(1.06); box-shadow: 0 4px 12px rgba(0,135,81,.35); }
.send-btn:disabled { opacity: .35; cursor: not-allowed; }

.powered-by {
    text-align: center;
    font-size: 9.5px;
    color: #c4c4c4;
    margin: 5px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.lang-tag {
    background: #f0fdf4;
    color: #166534;
    border-radius: 8px;
    padding: 1px 6px;
    font-size: 9px;
    font-weight: 600;
    border: 1px solid #bbf7d0;
}

/* Bouton toggle */
.chatbot-toggle {
    width: 54px;
    height: 54px;
    background: linear-gradient(135deg, #005c38 0%, #008751 100%);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(0,87,51,.45), 0 0 0 3px rgba(252,209,22,.3);
    transition: all .3s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
}

.chatbot-toggle:hover {
    transform: scale(1.1) rotate(-5deg);
    box-shadow: 0 6px 28px rgba(0,87,51,.55), 0 0 0 4px rgba(252,209,22,.4);
}

.chatbot-toggle.is-open { background: linear-gradient(135deg, #374151, #4b5563); }

.toggle-icon { display: flex; align-items: center; justify-content: center; }

.notif-badge {
    position: absolute;
    top: -2px;
    right: -2px;
    background: #E8112D;
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
    .chatbot-window { width: calc(100vw - 32px); max-height: 75vh; bottom: 66px; right: 0; }
    .chatbot-widget { bottom: 16px; right: 16px; }
    .artisan-actions { grid-template-columns: 1fr 1fr; }
}
</style>

{{-- ===== SCRIPT ===== --}}
<script>
function chatbot() {
    return {
        isOpen: false,
        mode: 'chat',          // 'chat' | 'artisan'
        messages: [],
        input: '',
        loading: false,
        language: 'fr',
        unreadCount: 0,
        activeAction: null,    // 'product' | 'bio' | 'story' | 'translate'
        generatedText: '',
        copied: false,

        // Formulaires du mode artisan
        productForm: { name: '', category: '', materials: '', ethnic_origin: '' },
        bioForm: { name: '', craft: '', city: '', experience: '', specialties: '' },
        storySubject: '',
        translateText: '',
        translateTarget: 'fon',

        suggestions: [
            { emoji: '🎭', text: 'Masque Guèlèdè' },
            { emoji: '🍲', text: 'C\'est quoi l\'Amiwo ?' },
            { emoji: '🏺', text: 'Trouve-moi un potier' },
            { emoji: '🌍', text: 'Traduis "bienvenue" en Fon' },
            { emoji: '📖', text: 'Raconte le Vaudou béninois' },
            { emoji: '🤲', text: 'Histoire du Zangbeto' },
        ],

        init() {
            this.messages.push({
                role: 'assistant',
                content: "Nǔ nyɔ ! Je suis **Anansi** 🕷️, l'araignée-conteur du Bénin.\n\nJe parle **français, anglais, Fon** et **Yoruba** — et je connais l'histoire derrière chaque masque, chaque tissu, chaque plat de nos terres.\n\nQue veux-tu savoir ou écrire aujourd'hui ?",
                time: this.getTime()
            });
            try {
                const saved = localStorage.getItem('anansiHistory');
                if (saved) {
                    const parsed = JSON.parse(saved);
                    if (parsed.length > 1) this.messages = parsed;
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

        toggleMode() {
            this.mode = this.mode === 'chat' ? 'artisan' : 'chat';
            this.activeAction = null;
            this.generatedText = '';
        },

        startAction(action) {
            this.activeAction = action;
            this.generatedText = '';
        },

        langFlag() {
            return { fr: '🇫🇷', en: '🇬🇧', fon: '🇧🇯', yoruba: '🌍' }[this.language] || '🌐';
        },

        langName() {
            return { fr: 'Français', en: 'English', fon: 'Fon', yoruba: 'Yoruba' }[this.language] || '';
        },

        inputPlaceholder() {
            return {
                fr: 'Pose ta question à Anansi...',
                en: 'Ask Anansi anything...',
                fon: 'Ɖɔ nǔ e a jló...',
                yoruba: 'Bi Anansi lere...',
            }[this.language] || 'Pose ta question...';
        },

        getTime() {
            return new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        },

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
            this.messages.push({ role: 'user', content: userMessage, time: this.getTime() });
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
                    body: JSON.stringify({ message: userMessage, language: this.language })
                });

                const data = await response.json();
                this.messages.push({
                    role: 'assistant',
                    content: data.reply || "Désolé, je n'ai pas pu répondre.",
                    time: this.getTime()
                });

                if (!this.isOpen) this.unreadCount++;
                this.saveHistory();
            } catch (error) {
                this.messages.push({
                    role: 'assistant',
                    content: "Désolé, une erreur s'est produite. Veuillez réessayer.",
                    time: this.getTime()
                });
            } finally {
                this.loading = false;
                this.$nextTick(() => this.scrollToBottom());
            }
        },

        async generate(type) {
            this.loading = true;
            this.generatedText = '';

            const payload = { type, language: this.language };

            if (type === 'product') Object.assign(payload, this.productForm);
            else if (type === 'bio') Object.assign(payload, this.bioForm);
            else if (type === 'story') payload.subject = this.storySubject;

            try {
                const response = await fetch('{{ route("anansi.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                this.generatedText = data.text || 'Anansi n\'a pas pu générer le texte.';
            } catch (error) {
                this.generatedText = 'Erreur de connexion. Réessaie dans un instant.';
            } finally {
                this.loading = false;
            }
        },

        async translateAction() {
            if (!this.translateText.trim()) return;
            this.loading = true;
            this.generatedText = '';

            // Passe par le chat normal avec une demande de traduction
            const msg = `Traduis ce texte en ${this.translateTarget === 'fon' ? 'Fon' : this.translateTarget === 'yoruba' ? 'Yoruba' : this.translateTarget === 'en' ? 'anglais' : 'français'} : "${this.translateText}"`;

            try {
                const response = await fetch('{{ route("chatbot.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message: msg, language: this.language })
                });

                const data = await response.json();
                this.generatedText = data.reply || 'Traduction indisponible.';
            } catch (error) {
                this.generatedText = 'Erreur de connexion.';
            } finally {
                this.loading = false;
            }
        },

        handleSuggestion(text) {
            this.input = text;
            this.sendMessage();
        },

        changeLanguage(lang) {
            this.language = lang;
            const greetings = {
                fr: "Langue changée en **Français** 🇫🇷 — Je réponds désormais en français.",
                en: "Language set to **English** 🇬🇧 — I'll now answer in English.",
                fon: "Mi ɖɔ Fon 🇧🇯 — *\"Nǔ nyɔ\"* ! Je vais intégrer des mots Fon dans mes réponses.",
                yoruba: "A yipada si Yoruba 🌍 — *\"E kaabo\"* ! Je vais intégrer des mots Yoruba dans mes réponses.",
            };
            this.messages.push({ role: 'assistant', content: greetings[lang] || '', time: this.getTime() });
        },

        copyText(text) {
            navigator.clipboard?.writeText(text.replace(/<[^>]+>/g, '').replace(/\*\*/g, ''));
        },

        copyGenerated() {
            const plain = this.generatedText.replace(/<[^>]+>/g, '').replace(/\*\*/g, '').replace(/\*/g, '');
            navigator.clipboard?.writeText(plain);
            this.copied = true;
            setTimeout(() => this.copied = false, 2000);

            // Dispatch event pour remplir un champ si la page écoute
            window.dispatchEvent(new CustomEvent('anansi:generated', { detail: { text: plain } }));
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
