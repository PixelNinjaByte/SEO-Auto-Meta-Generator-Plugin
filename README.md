# ⭐ AI SEO Meta Generator (Gemini)

A WordPress plugin that automatically generates **SEO-friendly meta descriptions** using the **Google Gemini AI API**.  
It also includes a full **WordPress admin panel**, **Gemini API key settings**, and an **AI SEO Playground** where you can manually test and generate descriptions.

This project is built for WordPress developers who want to integrate AI into SEO workflows.

---

## 🚀 Features

- 🔮 **Auto-Generate Meta Descriptions** using Gemini API  
- ⚡ Automatically saves meta description as post meta  
- 📝 Injects `<meta name="description">` dynamically  
- 🔑 Gemini API key settings page  
- 🧪 AI Playground inside WP Admin  
- 📘 Built-in instructions page  
- 🧩 Clean plugin architecture (separated includes)

---

## 📂 Folder Structure

ai-seo-meta/
│
├── ai-seo-meta.php
├── includes/
│ ├── admin-page.php
│ ├── gemini-client.php
│ └── meta-generator.php
└── readme.txt

---

## 🔧 Installation

1. Download or clone this repository:
   
   git clone https://github.com/PixelNinjaByte/SEO-Auto-Meta-Generator-Plugin

2. Upload the folder to:

   /wp-content/plugins/

3. Activate the plugin through the 'Plugins' menu in WordPress.

4. Open the new menu item:

   AI SEO

5. Enter your Gemini API key and save settings.