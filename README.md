# AI CV Chatbot — WordPress Plugin

A WordPress plugin that turns your CV into a conversational AI chatbot, powered by the Google Gemini API. Visitors can ask questions about your background, skills, and availability in plain language — and get real answers, not just a PDF download link.

Built as a personal portfolio project by [Sandor Kardos](https://sandorkardos.com), a Creative Technologist and AI Product Designer based in Edinburgh. Live demo at [sandorkardos.com](https://sandorkardos.com).

---

## What It Does

Instead of a static CV page, you get an AI assistant that:

- Answers questions about your background, skills, projects, and availability
- Responds in whatever language the visitor writes in (80+ languages supported)
- Shows context-matched CTAs after each response: CV download, LinkedIn, GitHub, email, phone, Behance, blog, and more
- Injects clickable blog links inline when the AI mentions your blog
- Handles off-topic questions with dry wit and redirects naturally
- Remembers the last 5 turns of the conversation for follow-up questions

---

## Features

### AI & Prompt Layer
- Powered by Google Gemini API (`gemini-2.5-flash` with automatic fallback to `gemini-2.5-flash-lite` and `gemini-1.5-flash`)
- Fully dynamic system prompt constructed from your admin settings and CV content
- 11 strict rules baked into every prompt, not overrideable by user messages
- Rolling conversation history (last 5 turns, stored as WordPress transients)
- 2048 max output tokens, no artificial word limit

### Language Detection
- Mirrors whatever language the visitor writes in, no confirmation flow
- Browser language passed as a hint, query language takes priority
- 80+ language map with locale normalisation (e.g. `hu-HU` resolves to Hungarian)
- Toggle on/off in admin settings

### Persona Modes
- **AI Assistant**: speaks on your behalf, third-person framing where needed
- **AI Clone**: speaks as you, first person throughout
- Neither mode introduces itself unprompted

### Tone Styles
- **Building in Public** (default): honest, technical, dry wit welcome
- **Professional**: polished, subtle wit
- **Casual**: friendly and warm
- **Basic**: simple and direct

### Smart CTA System
Every response is followed by a context-matched link bar, ranked by relevance:

| Query topic | Links shown |
|---|---|
| CV / resume / download | CV PDF + LinkedIn |
| Design / portfolio / UX | Behance + portfolio |
| Code / GitHub / automation | GitHub + live projects |
| Contact / hire / internship | Email + phone + CV + LinkedIn |
| Education / degree | LinkedIn + CV download |
| Blog / writing / case study | Blog (first) + LinkedIn |
| Video / YouTube | YouTube channel |
| Default fallback | Linktree > LinkedIn > Portfolio |

Hungarian equivalents included for all keyword groups.

### Inline Blog Link Injection
After generation, the server scans the response for any mention of the blog in English or Hungarian and injects a live hyperlink at that exact point in the sentence.

### Security
- **Honeypot field**: hidden input bots fill in, silently rejected
- **Timing check**: submissions under 2 seconds after page load rejected
- **Rate limiting**: 15 requests per IP per hour via WordPress transients
- **Nonce verification** on every AJAX request
- **300 character input limit** on both client and server
- **Prompt injection protection**: regex pattern library covering role override attempts, jailbreak patterns, prompt leaking, JSON injection, and token boundary tricks
- **Post-generation sanitisation**: strips leaked system markers, normalises formatting artefacts

---

## Requirements

- WordPress 5.8+
- PHP 7.4+
- A Google Gemini API key (free tier works, paid tier recommended for production)

---

## Installation

1. Download the latest release zip
2. In WordPress admin: Plugins > Add New > Upload Plugin
3. Upload the zip, activate
4. Go to Settings > CV Chatbot
5. Enter your Gemini API key, personal details, social links, and CV content
6. Add `[cv_chatbot]` shortcode to any page or post

---

## Configuration

The settings page has five sections:

**API Configuration**
Your Gemini API key. Get one free at [aistudio.google.com](https://aistudio.google.com).

**Personal Information**
Name, email, phone (rendered as a `tel:` link), CV download URL.

**Social & Portfolio Links**
LinkedIn, GitHub, portfolio site, blog, Linktree, Behance, X/Twitter, YouTube, Instagram.

**Appearance & Tone**
Background mode (dark/light), persona mode (Assistant/Clone), response tone, language detection toggle.

**CV Content**
Paste your CV as plain text. The AI CV Parser tool lets you paste raw CV text and have Gemini reformat it into the ideal prompt format automatically.

> All settings are stored in `wp_options` and survive plugin updates.

---

## Shortcode

```
[cv_chatbot]
[cv_chatbot placeholder="Ask me about my work..."]
```

---

## Writing Your CV Content

The CV content field is the source of truth the AI uses. Write it as natural prose, first person, covering:

- Professional summary
- Work history with context and outcomes
- Education (newest first)
- Key projects with links
- Skills and tools
- Availability / what you're looking for

The more specific and honest you are, the better the responses. Bullet points are fine for the CV field even though the AI is instructed not to use them in responses.

---

## Tips

- Use the **Building in Public** tone if you want responses that sound like a real person, not a corporate bio
- Enable **language detection** if you have an international audience
- The **AI Clone** persona works well if your CV is written in strong first-person voice
- Keep your CV content under 4000 words for best Gemini performance
- Set the blog URL in Social Links so inline blog link injection works

---

## How It Was Built

This plugin was built as part of a portfolio project exploring AI-assisted product development. The full case study, including all 15+ versions, debugging sessions, and design decisions, is written up at [sandorkardos.com/blog](https://sandorkardos.com/blog).

The PHP was written with AI tooling (Claude and Gemini). The architecture, product decisions, prompt engineering, and 15+ iterations of debugging were done by [Sandor Kardos](https://linkedin.com/in/sandor-kardos).

---

## Changelog

### v1.5.8
- Bot protection: honeypot field + timing check
- Token limit raised to 2048
- Availability/hiring response rule added (rule 11)
- Contact CTA now includes CV download alongside email and phone
- Hungarian CTA keywords expanded

### v1.5.7
- Gemini 2.5 Flash as primary model
- Endpoint corrected to v1beta (required for 2.5 models)

### v1.5.x
- Error logging with HTTP response details
- Inline blog link injection (English and Hungarian)
- Smart pointer rule (rule 10): never points to site root
- Wit handling for off-topic questions

### v1.4.x
- AI CV Parser tool in admin
- Building in Public tone mode
- Language detection with 80+ language map
- Persona modes (Assistant / Clone)
- Bilingual prompt support

### v1.4.0
- Full rebuild: injection protection, 8 social links, tone modes, rate limiting

---

## Support

If this plugin saved you time or landed you a job, a coffee is appreciated.

[![Ko-fi](https://ko-fi.com/img/githubbutton_sm.svg)](https://ko-fi.com/sandorko)

---

## License

GPL-2.0-or-later

---

## Author

**Sandor Kardos** — Creative Technologist and AI Product Designer, Edinburgh

- Website: [sandorkardos.com](https://sandorkardos.com)
- LinkedIn: [linkedin.com/in/sandor-kardos](https://linkedin.com/in/sandor-kardos)
- GitHub: [github.com/sanyi8](https://github.com/sanyi8)
- Behance: [behance.net/kardoss](https://behance.net/kardoss)
- Linktree: [linktr.ee/kardoss](https://linktr.ee/kardoss)
