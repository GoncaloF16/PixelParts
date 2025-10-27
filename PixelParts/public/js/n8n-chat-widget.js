(function () {
    const DEFAULTS = {
        title: "Assistente",
        welcome: "Olá! Em que posso ajudar?",
        typing: "a escrever…",
        placeholder: "Escreve a tua mensagem…",
        position: "bottom-right", // bottom-right | bottom-left
        color: "#4F46E5",
        endpoint: null,
        sessionKey: "n8nChatSessionId",
        headers: {}, // ex.: {"x-api-key":"..."} (configurável via data-headers='{"x-api-key":"..."}')
        autoresizeMobile: true,
    };

    // Pequena função de query por data-* com fallback a atributo do <script>
    function getConfig() {
        const hostDiv = document.getElementById("n8n-chat");
        const scriptEl =
            document.currentScript ||
            document.querySelector('script[src*="n8n-chat-widget.js"]');
        const srcConf = (el, name, def) => {
            if (!el) return def;
            const v = el.getAttribute("data-" + name);
            return v !== null ? v : def;
        };
        const parseJSON = (v) => {
            try {
                return JSON.parse(v);
            } catch {
                return null;
            }
        };

        const endpoint = srcConf(
            hostDiv,
            "endpoint",
            srcConf(scriptEl, "endpoint", DEFAULTS.endpoint)
        );

        return {
            title: srcConf(
                hostDiv,
                "title",
                srcConf(scriptEl, "title", DEFAULTS.title)
            ),
            welcome: srcConf(
                hostDiv,
                "welcome",
                srcConf(scriptEl, "welcome", DEFAULTS.welcome)
            ),
            typing: srcConf(
                hostDiv,
                "typing",
                srcConf(scriptEl, "typing", DEFAULTS.typing)
            ),
            placeholder: srcConf(
                hostDiv,
                "placeholder",
                srcConf(scriptEl, "placeholder", DEFAULTS.placeholder)
            ),
            position: srcConf(
                hostDiv,
                "position",
                srcConf(scriptEl, "position", DEFAULTS.position)
            ),
            color: srcConf(
                hostDiv,
                "color",
                srcConf(scriptEl, "color", DEFAULTS.color)
            ),
            endpoint,
            sessionKey: srcConf(
                hostDiv,
                "session-key",
                srcConf(scriptEl, "session-key", DEFAULTS.sessionKey)
            ),
            headers:
                parseJSON(
                    srcConf(
                        hostDiv,
                        "headers",
                        srcConf(scriptEl, "headers", null)
                    )
                ) || DEFAULTS.headers,
            autoresizeMobile:
                srcConf(
                    hostDiv,
                    "autoresize-mobile",
                    srcConf(scriptEl, "autoresize-mobile", "true")
                ) !== "false",
        };
    }

    function ensureSession(idKey) {
        try {
            let id = localStorage.getItem(idKey);
            if (!id) {
                // crypto.randomUUID pode não existir em browsers muito antigos
                id =
                    crypto && crypto.randomUUID
                        ? crypto.randomUUID()
                        : String(Date.now()) +
                          Math.random().toString(16).slice(2);
                localStorage.setItem(idKey, id);
            }
            return id;
        } catch {
            // fallback sem localStorage
            return crypto && crypto.randomUUID
                ? crypto.randomUUID()
                : String(Date.now()) + Math.random().toString(16).slice(2);
        }
    }

    // Sanitização MUITO simples (para evitar injeções acidentais no innerHTML)
    function escapeHTML(s) {
        return s.replace(
            /[&<>"']/g,
            (c) =>
                ({
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#39;",
                }[c])
        );
    }

    function autoLink(text) {
        const urlRegex = /(https?:\/\/[^\s<>"']+)/g;
        return text.replace(
            urlRegex,
            (m) =>
                `<a href="${m}" target="_blank" rel="noopener" class="n8n-chat-link">${m}</a>`
        );
    }

    // Aceita respostas do n8n em JSON ({response:"..."}) ou string crua; também tenta n8n chat outputs tipo data[0].output
    function parseResponseText(t) {
        try {
            const j = JSON.parse(t);
            if (Array.isArray(j) && j[0] && j[0].output)
                return String(j[0].output);
            if (j && typeof j === "object" && "response" in j)
                return String(j.response);
            return t;
        } catch {
            return t;
        }
    }

    function formatMessage(s) {
        // suporta **negrito**, \n -> <br>, e meta-infos simples (horário/local/oradores/júri/chair/moderador)
        let text = String(s);
        text = escapeHTML(text);

        // negrito markdown
        text = text.replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>");

        // blocos especiais simples
        text = text.replace(
            /⏰\s*<strong>(.*?)<\/strong>/g,
            '<div class="n8n-time">⏰ <strong>$1</strong></div>'
        );
        text = text.replace(
            /(?:^|[\n])\s*Local:\s*(.*?)(?=\n|$)/g,
            '\n<div class="n8n-meta">📍 Local: $1</div>'
        );
        text = text.replace(
            /(?:^|[\n])\s*Oradores?:\s*(.*?)(?=\n|$)/g,
            '\n<div class="n8n-meta">👥 Oradores: $1</div>'
        );
        text = text.replace(
            /(?:^|[\n])\s*Moderador(?:a)?:\s*(.*?)(?=\n|$)/g,
            '\n<div class="n8n-meta">👤 Moderador: $1</div>'
        );
        text = text.replace(
            /(?:^|[\n])\s*Chair:\s*(.*?)(?=\n|$)/g,
            '\n<div class="n8n-meta">👤 Chair: $1</div>'
        );
        text = text.replace(
            /(?:^|[\n])\s*Júri:\s*(.*?)(?=\n|$)/g,
            '\n<div class="n8n-meta">👥 Júri: $1</div>'
        );

        text = text.replace(/\n\n/g, "<br><br>").replace(/\n/g, "<br>");
        text = autoLink(text);
        return text;
    }

    function buildUI(cfg) {
        // Botão toggle
        const btn = document.createElement("button");
        btn.id = "n8n-chat-toggle";
        btn.setAttribute("aria-label", "Abrir chat");
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
</svg>`;
        document.body.appendChild(btn);

        // Contentor
        const box = document.createElement("div");
        box.id = "n8n-chat-container";
        box.setAttribute("role", "dialog");
        box.setAttribute("aria-modal", "true");
        box.style.display = "none";

        box.innerHTML = `
      <div id="n8n-chat-header" style="--n8n-accent:${cfg.color}">
        <span>${escapeHTML(cfg.title)}</span>
        <button id="n8n-chat-close" aria-label="Fechar">×</button>
      </div>
      <div id="n8n-chat-messages" aria-live="polite" aria-relevant="additions"></div>
      <div id="n8n-chat-inputbar">
        <input id="n8n-chat-input" placeholder="${escapeHTML(
            cfg.placeholder
        )}" autocomplete="off" />
        <button id="n8n-chat-send" aria-label="Enviar">➤</button>
      </div>
    `;
        document.body.appendChild(box);

        if (cfg.position === "bottom-left") {
            btn.classList.add("n8n-left");
            box.classList.add("n8n-left");
        }

        // Abertura
        btn.addEventListener("click", () => {
            const opening = box.style.display === "none";
            box.style.display = opening ? "flex" : "none";
            if (opening && !box.dataset.booted) {
                bootWelcome();
                box.dataset.booted = "1";
            }
            if (opening) input.focus();
        });

        // Fechar
        box.querySelector("#n8n-chat-close").addEventListener(
            "click",
            () => (box.style.display = "none")
        );

        const messages = box.querySelector("#n8n-chat-messages");
        const input = box.querySelector("#n8n-chat-input");
        const sendBtn = box.querySelector("#n8n-chat-send");

        function addMsg(html, who) {
            const div = document.createElement("div");
            div.className =
                "n8n-msg " + (who === "user" ? "n8n-user" : "n8n-bot");
            div.innerHTML = html;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        function addTyping() {
            const t = document.createElement("div");
            t.id = "n8n-typing";
            t.className = "n8n-msg n8n-bot";
            t.innerHTML = `<em>${escapeHTML(cfg.typing)}</em>`;
            messages.appendChild(t);
            messages.scrollTop = messages.scrollHeight;
        }

        function removeTyping() {
            const t = messages.querySelector("#n8n-typing");
            if (t) t.remove();
        }

        function bootWelcome() {
            addTyping();
            setTimeout(() => {
                removeTyping();
                addMsg(
                    `<strong>${escapeHTML(cfg.title)}:</strong> ${formatMessage(
                        cfg.welcome
                    )}`,
                    "bot"
                );
            }, 900);
        }

        let sending = false;
        async function sendCurrent() {
            const val = input.value.trim();
            if (!val || sending) return;
            sending = true;
            addMsg(`<strong>Tu:</strong> ${escapeHTML(val)}`, "user");
            input.value = "";
            input.disabled = true;
            sendBtn.disabled = true;
            addTyping();
            try {
                const payload = {
                    message: val,
                    sessionId: ensureSession(cfg.sessionKey),
                };
                const res = await fetch(cfg.endpoint, {
                    method: "POST",
                    headers: Object.assign(
                        { "Content-Type": "application/json" },
                        cfg.headers
                    ),
                    body: JSON.stringify(payload),
                });
                const text = await res.text();
                removeTyping();
                const parsed =
                    parseResponseText(text) || "Sem resposta no momento.";
                addMsg(
                    `<strong>${escapeHTML(cfg.title)}:</strong> ${formatMessage(
                        parsed
                    )}`,
                    "bot"
                );
            } catch (e) {
                removeTyping();
                addMsg(
                    `<strong>${escapeHTML(
                        cfg.title
                    )}:</strong> Ocorreu um erro ao contactar o serviço. Tenta novamente.`,
                    "bot"
                );
            } finally {
                sending = false;
                input.disabled = false;
                sendBtn.disabled = false;
                input.focus();
            }
        }

        input.addEventListener("keydown", (e) => {
            if (e.key === "Enter") sendCurrent();
        });
        sendBtn.addEventListener("click", sendCurrent);

        // Mobile: ocupar ecrã inteiro (opcional)
        if (cfg.autoresizeMobile) {
            const mql = window.matchMedia("(max-width: 768px)");
            function applyMobile(e) {
                if (e.matches) {
                    box.classList.add("n8n-mobile");
                } else {
                    box.classList.remove("n8n-mobile");
                }
            }
            applyMobile(mql);
            mql.addEventListener("change", applyMobile);
        }
    }

    function init() {
        const cfg = getConfig();
        if (!cfg.endpoint) {
            console.warn(
                "[N8nChatWidget] Falta data-endpoint com o URL do webhook."
            );
            return;
        }
        buildUI(cfg);
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();
