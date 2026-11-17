// ElNeMeR Mega Hub Client-Side Application
// Architected by Eng. Mahmoud Bek ElNemer (@ElNeMeR70)

let currentLang = 'ar';
let allData = { suites: [], languages: [], tools: [] };
let filteredTools = [];
let selectedSuite = 'all';
let selectedLang = 'all';
let searchQuery = '';
let currentPage = 1;
const PAGE_SIZE = 48;

const i18n = {
  ar: {
    brand_title: "موسوعة وحلول النمر البرمجية",
    brand_sub: "المهندس محمود بك النمر · Eng. Mahmoud Bek ElNemer",
    github_profile: "GitHub (@ElNeMeR70)",
    hero_pill: "🚀 أكبر تكتل برمجي سيادي مستقل في العالم",
    hero_title: "فهرس الأدوات مفتوحة المصدر والذكاء الاصطناعي",
    hero_desc: "تصفح وبحث فوري في ترسانة برمجية تضم أكثر من 7,448 مستودعاً عاماً حياً و 7,201 أداة متخصصة بـ 10 لغات برمجية كبرى مع إمكانية التثبيت الفوري بنقرة واحدة.",
    stat_repos: "مستودع عام حي",
    stat_suites: "حزم سيادية كبرى",
    stat_langs: "لغات برمجية سيادية",
    stat_prs: "PRs (11 مدمج بـ main)",
    packages_title: "📦 حزم السجلات الرسمية (Official Package Registries)",
    filter_suite: "الحزمة التخصصية:",
    filter_lang: "لغة البرمجة:",
    all: "الكل",
    results_found: "أداة مطابقة",
    prev: "السابق",
    next: "التالي",
    loading: "جاري تحميل وتجهيز الفهرس الشامل للأدوات...",
    copy_clone: "نسخ الرابط",
    copied: "تم النسخ!",
    open_repo: "المستودع ↗",
    search_placeholder: "ابحث باسم الأداة، التقنية، أو الوظيفة (مثال: rag, agent, nlp, bachelier, merkle)...",
    footer_rights: "© 2026 المهندس محمود بك النمر (Eng. Mahmoud Bek ElNemer) · شركة حلول النمر الرقمية",
    theme_light: "فاتح",
    theme_dark: "داكن",
    ad_label: "إعلان معتمد / Sponsored",
    page_info: (cur, total) => `الصفحة ${cur} من ${total}`
  },
  en: {
    brand_title: "ElNeMeR Software & AI Encyclopedia",
    brand_sub: "Eng. Mahmoud Bek ElNemer · Founder & CEO",
    github_profile: "GitHub (@ElNeMeR70)",
    hero_pill: "🚀 World's Largest Sovereign Individual Open-Source Ecosystem",
    hero_title: "Open-Source & AI Tools Mega Directory",
    hero_desc: "Instant search across 7,448+ live public repositories and 7,201 specialized tools implemented in 10 polyglot languages with 1-click install.",
    stat_repos: "Live Public Repos",
    stat_suites: "Specialized Suites",
    stat_langs: "Polyglot Languages",
    stat_prs: "PRs (11 Merged on Main)",
    packages_title: "📦 Official Package Registries",
    filter_suite: "Specialized Suite:",
    filter_lang: "Language:",
    all: "All",
    results_found: "tools found",
    prev: "Previous",
    next: "Next",
    loading: "Loading sovereign tools catalogue...",
    copy_clone: "Copy Clone",
    copied: "Copied!",
    open_repo: "GitHub ↗",
    search_placeholder: "Search tool name, topic, or keyword (e.g. rag, agent, nlp, bachelier, merkle)...",
    footer_rights: "© 2026 Eng. Mahmoud Bek ElNemer · Nemer Digital Solutions",
    theme_light: "Light",
    theme_dark: "Dark",
    ad_label: "Sponsored Advertisement",
    page_info: (cur, total) => `Page ${cur} of ${total}`
  }
};

// Initialize
document.addEventListener("DOMContentLoaded", async () => {
  setupThemeToggle();
  setupLanguageToggle();
  setupSearch();
  await loadData();
});

function setupThemeToggle() {
  const btn = document.getElementById("themeToggle");
  const icon = document.getElementById("themeIcon");
  const label = document.getElementById("themeLabel");
  
  // Default to light theme as requested by user
  const savedTheme = localStorage.getItem("elnemer_theme") || "light";
  applyTheme(savedTheme);

  if (btn) {
    btn.addEventListener("click", () => {
      const current = document.documentElement.getAttribute("data-theme") || "light";
      const next = current === "light" ? "dark" : "light";
      applyTheme(next);
      localStorage.setItem("elnemer_theme", next);
    });
  }

  function applyTheme(th) {
    document.documentElement.setAttribute("data-theme", th);
    if (icon) icon.textContent = th === "light" ? "☀️" : "🌙";
    if (label) label.textContent = th === "light" ? i18n[currentLang].theme_light : i18n[currentLang].theme_dark;
  }
}

function setupLanguageToggle() {
  const btn = document.getElementById("langToggle");
  btn.addEventListener("click", () => {
    currentLang = currentLang === 'ar' ? 'en' : 'ar';
    document.documentElement.lang = currentLang;
    document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
    btn.textContent = currentLang === 'ar' ? 'English' : 'العربية';
    applyI18n();
    renderSuiteTabs();
    renderTools();
  });
}

function applyI18n() {
  const t = i18n[currentLang];
  document.querySelectorAll("[data-i18n]").forEach(el => {
    const key = el.getAttribute("data-i18n");
    if (t[key]) el.textContent = t[key];
  });
  const currentTheme = document.documentElement.getAttribute("data-theme") || "light";
  const themeLabel = document.getElementById("themeLabel");
  if (themeLabel) {
    themeLabel.textContent = currentTheme === "light" ? t.theme_light : t.theme_dark;
  }
  document.getElementById("searchInput").placeholder = t.search_placeholder;
}

async function loadData() {
  try {
    const resp = await fetch("tools.json");
    allData = await resp.json();
    renderSuiteTabs();
    renderLangTabs();
    applyFilter();
  } catch (err) {
    document.getElementById("toolsGrid").innerHTML = `<div style="color:#ef4444;text-align:center;padding:2rem;">Error loading tools index: ${err.message}</div>`;
  }
}

function renderSuiteTabs() {
  const container = document.getElementById("suiteTabs");
  const t = i18n[currentLang];
  container.innerHTML = `<button class="filter-pill ${selectedSuite === 'all' ? 'active' : ''}" data-suite="all">${t.all} (7,201+)</button>`;

  allData.suites.forEach((s, idx) => {
    const label = currentLang === 'ar' ? s.ar : s.en;
    const btn = document.createElement("button");
    btn.className = `filter-pill ${selectedSuite === String(idx) ? 'active' : ''}`;
    btn.setAttribute("data-suite", idx);
    btn.textContent = label;
    btn.addEventListener("click", () => {
      selectedSuite = String(idx);
      document.querySelectorAll(".filter-pill").forEach(p => p.classList.remove("active"));
      btn.classList.add("active");
      applyFilter();
    });
    container.appendChild(btn);
  });

  container.querySelector('[data-suite="all"]').addEventListener("click", (e) => {
    selectedSuite = 'all';
    document.querySelectorAll(".filter-pill").forEach(p => p.classList.remove("active"));
    e.target.classList.add("active");
    applyFilter();
  });
}

function renderLangTabs() {
  const container = document.getElementById("langTabs");
  const t = i18n[currentLang];
  container.innerHTML = `<button class="lang-pill ${selectedLang === 'all' ? 'active' : ''}" data-lang="all">${t.all}</button>`;

  allData.languages.forEach((l, idx) => {
    const btn = document.createElement("button");
    btn.className = `lang-pill ${selectedLang === String(idx) ? 'active' : ''}`;
    btn.setAttribute("data-lang", idx);
    btn.textContent = l.toUpperCase();
    btn.addEventListener("click", () => {
      selectedLang = String(idx);
      document.querySelectorAll(".lang-pill").forEach(p => p.classList.remove("active"));
      btn.classList.add("active");
      applyFilter();
    });
    container.appendChild(btn);
  });

  container.querySelector('[data-lang="all"]').addEventListener("click", (e) => {
    selectedLang = 'all';
    document.querySelectorAll(".lang-pill").forEach(p => p.classList.remove("active"));
    e.target.classList.add("active");
    applyFilter();
  });
}

function setupSearch() {
  const input = document.getElementById("searchInput");
  const clearBtn = document.getElementById("clearSearch");

  let timeout = null;
  input.addEventListener("input", (e) => {
    searchQuery = e.target.value.trim().toLowerCase();
    clearBtn.style.display = searchQuery ? "block" : "none";
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      currentPage = 1;
      applyFilter();
    }, 150);
  });

  clearBtn.addEventListener("click", () => {
    input.value = "";
    searchQuery = "";
    clearBtn.style.display = "none";
    currentPage = 1;
    applyFilter();
  });

  document.getElementById("prevPage").addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      renderTools();
      window.scrollTo({ top: 400, behavior: 'smooth' });
    }
  });

  document.getElementById("nextPage").addEventListener("click", () => {
    const maxPage = Math.ceil(filteredTools.length / PAGE_SIZE) || 1;
    if (currentPage < maxPage) {
      currentPage++;
      renderTools();
      window.scrollTo({ top: 400, behavior: 'smooth' });
    }
  });

  // Modal close
  document.getElementById("modalClose").addEventListener("click", closeModal);
  document.getElementById("modalBackdrop").addEventListener("click", (e) => {
    if (e.target.id === "modalBackdrop") closeModal();
  });
}

function applyFilter() {
  filteredTools = allData.tools.filter(item => {
    // suite filter
    if (selectedSuite !== 'all' && item.s !== Number(selectedSuite)) return false;
    // lang filter
    if (selectedLang !== 'all' && item.l !== Number(selectedLang)) return false;
    // search filter
    if (searchQuery) {
      const matchName = item.n.toLowerCase().includes(searchQuery);
      const matchTitle = item.t.toLowerCase().includes(searchQuery);
      if (!matchName && !matchTitle) return false;
    }
    return true;
  });

  document.getElementById("matchCount").textContent = filteredTools.length.toLocaleString();
  currentPage = 1;
  renderTools();
}

function renderTools() {
  const grid = document.getElementById("toolsGrid");
  const t = i18n[currentLang];
  const total = filteredTools.length;
  const maxPage = Math.ceil(total / PAGE_SIZE) || 1;

  document.getElementById("pageInfo").textContent = t.page_info(currentPage, maxPage);
  document.getElementById("prevPage").disabled = currentPage <= 1;
  document.getElementById("nextPage").disabled = currentPage >= maxPage;

  if (total === 0) {
    grid.innerHTML = `<div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text-muted);">
      <p style="font-size:1.5rem;margin-bottom:0.5rem;">🔍</p>
      <p>لا توجد أدوات مطابقة لبحثك الحالي.</p>
    </div>`;
    return;
  }

  const start = (currentPage - 1) * PAGE_SIZE;
  const slice = filteredTools.slice(start, start + PAGE_SIZE);

  let cardsHtml = '';
  slice.forEach((item, index) => {
    const suite = allData.suites[item.s];
    const suiteName = currentLang === 'ar' ? suite.ar : suite.en;
    const lang = allData.languages[item.l];
    const repoUrl = `https://github.com/ElNeMeR70/${item.n}`;
    const cloneCmd = `git clone ${repoUrl}.git`;
    const toolPageUrl = `tool.php?name=${encodeURIComponent(item.n)}`;

    cardsHtml += `
      <div class="tool-card" onclick="openDetail('${item.n}')">
        <div>
          <div class="card-tags">
            <span class="suite-badge">${suiteName}</span>
            <span class="lang-badge">${lang}</span>
          </div>
          <h4 class="tool-title" style="margin-top:0.75rem;">${item.t}</h4>
          <p class="tool-name-code" style="margin-top:0.35rem;">${item.n}</p>
        </div>
        <div class="card-actions" onclick="event.stopPropagation()" style="flex-wrap:wrap; gap:0.4rem;">
          <a href="${toolPageUrl}" class="btn btn-outline" style="background:#ffffff; color:#0284c7; border:1px solid #bae6fd; font-weight:700; font-size:0.8rem;">
            📄 الشرح والتثبيت
          </a>
          <button class="btn btn-ghost" onclick="copyCode('${cloneCmd}', this)" style="font-size:0.8rem;">
            <span>📋</span> <span class="btn-text">${t.copy_clone}</span>
          </button>
          <a href="${repoUrl}" target="_blank" rel="noopener" class="btn btn-outline" style="font-size:0.8rem;">
            ${t.open_repo}
          </a>
        </div>
      </div>
    `;

    // Insert high-converting Multiplex Ad unit every 12 cards
    if ((index + 1) % 12 === 0 && index !== slice.length - 1) {
      cardsHtml += `
        <div class="tool-card tool-card-ad" style="grid-column: 1 / -1; padding: 1.25rem; background: #ffffff; border: 1px dashed #0284c7; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
          <span class="ad-disclosure" style="color:#0284c7; font-weight:800; margin-bottom:0.5rem; display:block;">إعلان مقترح للمطورين / Developer Ad</span>
          <ins class="adsbygoogle"
               style="display:block"
               data-ad-format="autorelaxed"
               data-ad-client="ca-pub-6374003238265902"
               data-ad-slot="2872380691"></ins>
        </div>
      `;
    }
  });

  grid.innerHTML = cardsHtml;
  try { (adsbygoogle = window.adsbygoogle || []).push({}); } catch(e){}
}

function openDetail(name) {
  const item = allData.tools.find(t => t.n === name);
  if (!item) return;

  const suite = allData.suites[item.s];
  const lang = allData.languages[item.l];
  const repoUrl = `https://github.com/ElNeMeR70/${item.n}`;
  const cloneCmd = `git clone ${repoUrl}.git`;
  const toolPageUrl = `tool.php?name=${encodeURIComponent(item.n)}`;

  const content = document.getElementById("modalContent");
  content.innerHTML = `
    <div style="display:flex;gap:0.5rem;margin-bottom:1rem;">
      <span class="suite-badge">${currentLang === 'ar' ? suite.ar : suite.en}</span>
      <span class="lang-badge">${lang}</span>
    </div>
    <h3 style="font-size:1.4rem;font-weight:800;margin-bottom:0.5rem;">${item.t}</h3>
    <p style="font-family:var(--font-mono);color:var(--text-muted);font-size:0.85rem;margin-bottom:1.5rem;">${item.n}</p>

    <div style="margin-bottom:1.25rem;">
      <label style="font-size:0.85rem;font-weight:700;color:var(--text-muted);display:block;margin-bottom:0.35rem;">Clone Command:</label>
      <div class="code-box">
        <code>${cloneCmd}</code>
        <button class="copy-btn" onclick="copyCode('${cloneCmd}', this)">📋</button>
      </div>
    </div>

    <!-- AdSense banner in modal -->
    <div style="margin: 1.5rem 0; padding: 0.75rem; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px; text-align:center;">
      <span style="font-size:0.65rem; color:#94a3b8; font-weight:700; display:block; margin-bottom:0.25rem;">SPONSORED</span>
      <ins class="adsbygoogle"
           style="display:block"
           data-ad-client="ca-pub-6374003238265902"
           data-ad-slot="2872380691"
           data-ad-format="auto"
           data-full-width-responsive="true"></ins>
    </div>

    <div style="display:flex;gap:0.75rem;margin-top:1.5rem;flex-wrap:wrap;">
      <a href="${toolPageUrl}" target="_blank" class="btn btn-outline" style="flex:1;justify-content:center;background:#0284c7;color:#fff;border-color:#0284c7;font-weight:700;min-width:200px;">
        📄 صفحة الشرح والمواصفات الكاملة (SEO Page) ↗
      </a>
      <a href="${repoUrl}" target="_blank" rel="noopener" class="btn btn-outline" style="flex:1;justify-content:center;background:#ffffff;color:#0f172a;border-color:#cbd5e1;font-weight:700;min-width:160px;">
        GitHub ↗
      </a>
    </div>
  `;

  document.getElementById("modalBackdrop").style.display = "flex";
  try { (adsbygoogle = window.adsbygoogle || []).push({}); } catch(e){}
}

function closeModal() {
  document.getElementById("modalBackdrop").style.display = "none";
}

window.copyCode = function(text, btn) {
  navigator.clipboard.writeText(text).then(() => {
    const textSpan = btn.querySelector('.btn-text');
    const original = textSpan ? textSpan.textContent : btn.textContent;
    if (textSpan) textSpan.textContent = i18n[currentLang].copied;
    else btn.textContent = "✅";
    setTimeout(() => {
      if (textSpan) textSpan.textContent = original;
      else btn.textContent = "📋";
    }, 1500);
  });
};
