/**
 * CV Chatbot Frontend — v1.5.8
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var widget       = document.getElementById('cv-chatbot-widget');
        var input        = document.getElementById('cv-query-input');
        var button       = document.getElementById('cv-send-btn');
        var responseArea = document.getElementById('cv-response-area');

        if (!widget || !input || !button || !responseArea) return;

        var browserLang = (navigator.language || navigator.userLanguage || '').split(',')[0].trim();

        // ── Send query ──────────────────────────────────────────────────
        function sendQuery() {
            var query = input.value.trim();
            if (!query) return;

            if (query.length > 300) {
                showError('Please keep your question under 300 characters.');
                return;
            }

            setLoading(true);
            responseArea.innerHTML = '<div class="cv-loading-indicator">Thinking\u2026</div>';

            var honeypot  = document.getElementById('cv_website');
            var loadTime  = document.getElementById('cv_load_time');

            var fd = new FormData();
            fd.append('action',       'cv_chatbot_query');
            fd.append('nonce',        cvChatbot.nonce);
            fd.append('query',        query);
            fd.append('browser_lang', cvChatbot.lang_detect === '1' ? browserLang : '');
            fd.append('cv_website',   honeypot  ? honeypot.value  : '');
            fd.append('load_time',    loadTime  ? loadTime.value  : '0');

            fetch(cvChatbot.ajax_url, { method: 'POST', body: fd })
                .then(function (r) {
                    if (!r.ok) throw new Error('Network error');
                    return r.json();
                })
                .then(function (data) {
                    if (data.success) {
                        var html = '<div class="cv-response-text">' + data.data.response + '</div>';
                        if (data.data.cta) {
                            html += '<div class="cv-cta-text">' + data.data.cta + '</div>';
                        }
                        responseArea.innerHTML = html;
                    } else {
                        showError(data.data || 'Something went wrong. Please try again.');
                    }
                })
                .catch(function () {
                    showError('Connection error. Please try again.');
                })
                .finally(function () {
                    setLoading(false);
                    input.value = '';
                    input.focus();
                });
        }

        // ── Helpers ─────────────────────────────────────────────────────
        function setLoading(on) {
            button.disabled = on;
            input.disabled  = on;
        }

        function showError(msg) {
            responseArea.innerHTML = '<div class="cv-response-text cv-error">' + escHtml(msg) + '</div>';
        }

        function escHtml(str) {
            if (typeof str !== 'string') return '';
            var d = document.createElement('div');
            d.textContent = str;
            return d.innerHTML;
        }

        function validateLength() {
            var len = input.value.trim().length;
            if (len > 300) {
                input.style.borderColor = '#ef4444';
            } else if (input.style.borderColor === 'rgb(239, 68, 68)') {
                input.style.borderColor = '';
            }
        }

        // ── Event listeners ─────────────────────────────────────────────
        button.addEventListener('click', sendQuery);

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); sendQuery(); }
            if (e.key === 'Escape') { input.value = ''; input.blur(); }
        });

        input.addEventListener('input', validateLength);

        // Accessibility: focus ring on widget
        input.addEventListener('focus', function () { widget.classList.add('cv-focused'); });
        input.addEventListener('blur',  function () { widget.classList.remove('cv-focused'); });
    });

})();
