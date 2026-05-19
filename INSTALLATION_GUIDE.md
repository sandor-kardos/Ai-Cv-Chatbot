# WordPress Installation Guide - CV Chatbot

## Quick Installation Steps

### 1. Upload Plugin Files
1. Download all plugin files from this project
2. Create a folder called `cv-chatbot` in your WordPress `/wp-content/plugins/` directory
3. Upload these files to the `cv-chatbot` folder:
   - `cv-chatbot.php`
   - `includes/class-cv-chatbot.php`
   - `includes/admin-settings.php`
   - `assets/style.css`
   - `assets/script.js`

### 2. Activate Plugin
1. Go to your WordPress admin dashboard
2. Navigate to **Plugins** → **Installed Plugins**
3. Find "AI CV Chatbot" and click **Activate**

### 3. Configure Settings
1. Go to **Settings** → **CV Chatbot**
2. Add your **Gemini API Key** (get free key from [Google AI Studio](https://aistudio.google.com/app/apikey))
3. Update your personal information:
   - Your Name: Sandor Kardos
   - Email: contact@sandorkardos.com
   - LinkedIn: https://linkedin.com/in/sandor-kardos
   - GitHub: https://github.com/sanyi8
   - Portfolio: https://sandorkardos.com
4. The CV content is already filled with your professional background
5. Click **Save Settings**

### 4. Embed in Your Website
Add this shortcode to any page or post:

```
[cv_chatbot]
```

**Custom placeholder text:**
```
[cv_chatbot placeholder="Ask me about my experience..."]
```

## Where to Add the Shortcode

### In Pages/Posts
1. Edit any page or post
2. Add the shortcode `[cv_chatbot]` where you want the chatbot
3. Save/publish the page

### In Widgets
1. Go to **Appearance** → **Widgets**
2. Add a "Text" or "HTML" widget
3. Insert the shortcode `[cv_chatbot]`

### In Theme Templates
Add this PHP code in your theme files:
```php
<?php echo do_shortcode('[cv_chatbot]'); ?>
```

## Styling for Dark Themes

The chatbot is designed for dark backgrounds with:
- Transparent background
- Lime-green (#d8ff88) accents
- Light text (#f3f4f6)
- White input field for readability

## Getting Your Gemini API Key

1. Visit [Google AI Studio](https://aistudio.google.com/app/apikey)
2. Sign in with your Google account
3. Click "Create API Key"
4. Copy the key and paste it in the plugin settings

## Security Features

- Rate limiting: 15 requests per hour per IP
- Input sanitization and validation
- WordPress nonces for security
- Secure API key storage

## Troubleshooting

### Chatbot not responding
1. Check if Gemini API key is correctly entered
2. Verify your website has internet connection
3. Check if you've exceeded rate limits (wait 1 hour)

### Styling issues
1. Ensure your theme doesn't override the chatbot CSS
2. The chatbot is designed for dark backgrounds
3. You can customize colors in `assets/style.css`

### Rate limits
- Each IP can make 15 requests per hour
- This prevents API abuse and keeps costs low
- Users will see a rate limit message if exceeded

## Support

For technical issues or customization requests, contact Sandor Kardos:
- Website: https://sandorkardos.com
- LinkedIn: https://linkedin.com/in/sandor-kardos
- Professional Links: https://linktr.ee/kardoss