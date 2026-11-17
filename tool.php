<?php
/**
 * ElNeMeR Sovereign Tools Encyclopedia — Individual Tool SEO & Documentation Page
 * Architected by Eng. Mahmoud Bek ElNemer (@ElNeMeR70)
 * 100% Corporate Light Theme with Deep Bilingual (AR/EN) Support & High-Density AdSense
 */

// Load tools.json
$jsonFile = __DIR__ . '/tools.json';
if (!file_exists($jsonFile)) {
    die("Index file tools.json not found.");
}

$raw = file_get_contents($jsonFile);
$data = json_decode($raw, true);

$toolName = isset($_GET['name']) ? trim(preg_replace('/[^a-zA-Z0-9_\-]/', '', $_GET['name'])) : '';
$currentLang = isset($_GET['lang']) && strtolower($_GET['lang']) === 'en' ? 'en' : 'ar';

$tool = null;
if ($toolName && isset($data['tools'])) {
    foreach ($data['tools'] as $item) {
        if ($item['n'] === $toolName) {
            $tool = $item;
            break;
        }
    }
}

// Fallback to first tool if not found or empty
if (!$tool && isset($data['tools'][0])) {
    $tool = $data['tools'][0];
    $toolName = $tool['n'];
}

$suite = isset($data['suites'][$tool['s']]) ? $data['suites'][$tool['s']] : ['ar' => 'حزمة سيادية', 'en' => 'Sovereign Suite', 'name' => 'general'];
$lang = isset($data['languages'][$tool['l']]) ? $data['languages'][$tool['l']] : 'python';
$langUpper = strtoupper($lang);
$toolTitle = htmlspecialchars($tool['t']);
$toolSafeName = htmlspecialchars($tool['n']);
$suiteAr = htmlspecialchars($suite['ar']);
$suiteEn = htmlspecialchars($suite['en']);

// Arabic & English Descriptions
$descriptionsAr = [
    0 => "مكتبة برمجية متخصصة ومبنية بلغة {$langUpper} لمعالجة وفهرسة العلوم الشرعية والإسلامية، وتوفر خوارزميات فائقة السرعة للتحقق والتخريج والحساب الفقهي والفلكي الدقيق.",
    1 => "خوارزمية وهيكل بيانات متقدم متعدد اللغات بلغة {$langUpper} لحل المسائل الحسابية المعقدة في زمن تنفيذ قياسي واستغلال أمثل للذاكرة.",
    2 => "خادم ومحرك إجراءات متوافق مع بروتوكول MCP (Model Context Protocol) لتزويد نماذج الذكاء الاصطناعي بقدرات استدعاء دوال حية والربط السحابي المباشر.",
    3 => "أداة فحص واختبار أمني واختراق أخلاقي سيبراني بلغة {$langUpper} لكشف الثغرات، وتدقيق التشفير، ومراقبة حركة البيانات واكتشاف الشذوذ الأمني.",
    4 => "نموذج ذكاء اصطناعي ونظام أسراب وكلاء مستقلين (Multi-Agent Swarm) بلغة {$langUpper} لإدارة المهام الذاتية والتخطيط المنطقي المتوازي.",
    5 => "محرك معالجة لغة عربية طبيعية (Arabic NLP) يوفر تجذير الكلمات، الإعراب الآلي، ضبط التشكيل وتصنيف النصوص بدقة فائقة.",
    6 => "محرك تحليل مالي وإدارة مخاطر كمية (Quantitative Finance & Risk) بلغة {$langUpper} لحساب احتمالات السوق، تسعير المشتقات ونماذج المحافظ الاستثمارية.",
    7 => "نظام ذكاء اصطناعي معلوماتي حيوي (Bioinformatics & Genomic AI) لتحليل التسلسلات الجينومية والبروتينية والتنبؤ بالبنى الجزيئية."
];

$descriptionsEn = [
    0 => "High-performance {$langUpper} algorithmic library for processing, indexing, and verifying Islamic jurisprudence, Hadith chains (Isnad DAG), and astronomical solar-lunar calculations with microsecond execution.",
    1 => "Advanced polyglot data structures and algorithmic implementations in {$langUpper}, optimized for minimal memory footprint and extreme throughput under concurrent load.",
    2 => "Production-ready Model Context Protocol (MCP) server in {$langUpper}, providing LLMs and autonomous agents with low-latency tool execution and typed schemas.",
    3 => "Sovereign cyber defense and vulnerability assessment tool written in {$langUpper}, specializing in automated vulnerability discovery, cryptographic auditing, and network security inspection.",
    4 => "Autonomous multi-agent orchestration runtime in {$langUpper}, enabling parallel swarm collaboration, chain-of-thought planning, and deterministic execution.",
    5 => "Deep Arabic Natural Language Processing engine in {$langUpper}, featuring morphological stemmers, diacritic restoration, syntax parsers, and named entity recognition.",
    6 => "Institutional-grade quantitative analytics and financial risk engine in {$langUpper}, implementing Monte Carlo simulations, volatility surfaces, and Black-Scholes/Bachelier pricing.",
    7 => "Computational biology and genomic AI toolkit in {$langUpper}, engineered for sequence alignment, MSA matrix calculations, variant impact scoring, and structural analysis."
];

$descAr = isset($descriptionsAr[$tool['s']]) ? $descriptionsAr[$tool['s']] : "أداة برمجية سيادية مفتوحة المصدر من تطوير المهندس محمود بك النمر.";
$descEn = isset($descriptionsEn[$tool['s']]) ? $descriptionsEn[$tool['s']] : "Sovereign open-source software tool engineered by Eng. Mahmoud Bek ElNemer.";

$descCurrent = $currentLang === 'ar' ? $descAr : $descEn;
$suiteCurrent = $currentLang === 'ar' ? $suiteAr : $suiteEn;

// Canonical and alternate URLs
$canonicalBase = "https://elnemer-ai.com/megahub/tool.php?name=" . urlencode($toolSafeName);
$canonicalUrl = $canonicalBase . ($currentLang === 'en' ? '&lang=en' : '');
$urlAr = $canonicalBase . '&lang=ar';
$urlEn = $canonicalBase . '&lang=en';

$repoUrl = "https://github.com/ElNeMeR70/" . $toolSafeName;
$cloneCmd = "git clone {$repoUrl}.git";

// Language-specific install command
$installCmd = "";
switch ($lang) {
    case 'python': $installCmd = "pip install -e ."; break;
    case 'rust': $installCmd = "cargo build --release"; break;
    case 'go': $installCmd = "go build ./..."; break;
    case 'typescript': $installCmd = "npm install && npm run build"; break;
    case 'cpp': $installCmd = "cmake -B build && cmake --build build"; break;
    case 'csharp': $installCmd = "dotnet build -c Release"; break;
    case 'java': $installCmd = "mvn clean install"; break;
    case 'php': $installCmd = "composer install"; break;
    case 'dart': $installCmd = "dart pub get"; break;
    default: $installCmd = "make build";
}

// Find 6 related tools
$related = [];
if (isset($data['tools'])) {
    foreach ($data['tools'] as $candidate) {
        if ($candidate['s'] === $tool['s'] && $candidate['n'] !== $tool['n']) {
            $related[] = $candidate;
            if (count($related) >= 6) break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>" dir="<?= $currentLang === 'ar' ? 'rtl' : 'ltr' ?>" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <?php if ($currentLang === 'ar'): ?>
    <title><?= $toolTitle ?> (<?= $toolSafeName ?>) — أداة <?= $langUpper ?> في <?= $suiteAr ?> | م. محمود بك النمر</title>
    <meta name="description" content="<?= htmlspecialchars($descAr) ?> شرح تفصيلي، طريقة التثبيت والاستخدام المباشر، ورابط المستودع على GitHub.">
    <meta name="keywords" content="<?= $toolSafeName ?>, <?= $toolTitle ?>, أداة <?= $langUpper ?>, مكتبة <?= $langUpper ?>, <?= $suiteAr ?>, المهندس محمود بك النمر, ElNeMeR70, ذكاء اصطناعي مفتوح المصدر">
  <?php else: ?>
    <title><?= $toolTitle ?> (<?= $toolSafeName ?>) — Sovereign <?= $langUpper ?> Tool in <?= $suiteEn ?> | Eng. Mahmoud Bek ElNemer</title>
    <meta name="description" content="<?= htmlspecialchars($descEn) ?> Complete documentation, quick installation commands, and open-source GitHub repository.">
    <meta name="keywords" content="<?= $toolSafeName ?>, <?= $toolTitle ?>, <?= $langUpper ?> tool, <?= $langUpper ?> library, <?= $suiteEn ?>, Eng. Mahmoud Bek ElNemer, ElNeMeR70, open source AI">
  <?php endif; ?>

  <meta name="author" content="Eng. Mahmoud Bek ElNemer">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="<?= $canonicalUrl ?>">
  <link rel="alternate" hreflang="ar" href="<?= $urlAr ?>">
  <link rel="alternate" hreflang="en" href="<?= $urlEn ?>">
  <link rel="alternate" hreflang="x-default" href="<?= $canonicalBase ?>">

  <!-- Open Graph -->
  <meta property="og:type" content="article">
  <meta property="og:url" content="<?= $canonicalUrl ?>">
  <meta property="og:title" content="<?= $toolTitle ?> (<?= $toolSafeName ?>) — <?= $langUpper ?>">
  <meta property="og:description" content="<?= htmlspecialchars($descCurrent) ?>">
  <meta property="og:site_name" content="ElNeMeR AI Mega Hub">
  <meta property="og:locale" content="<?= $currentLang === 'ar' ? 'ar_AR' : 'en_US' ?>">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:creator" content="@ElNeMeR70">
  <meta name="twitter:title" content="<?= $toolTitle ?> (<?= $toolSafeName ?>)">
  <meta name="twitter:description" content="<?= htmlspecialchars($descCurrent) ?>">

  <!-- JSON-LD Structured Data: SoftwareApplication & Breadcrumb -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "SoftwareApplication",
        "name": "<?= $toolTitle ?>",
        "alternateName": "<?= $toolSafeName ?>",
        "operatingSystem": "All",
        "applicationCategory": "DeveloperApplication",
        "programmingLanguage": "<?= $langUpper ?>",
        "description": "<?= addslashes($descCurrent) ?>",
        "url": "<?= $canonicalUrl ?>",
        "codeRepository": "<?= $repoUrl ?>",
        "author": {
          "@type": "Person",
          "name": "Eng. Mahmoud Bek ElNemer",
          "url": "https://github.com/ElNeMeR70"
        }
      },
      {
        "@type": "BreadcrumbList",
        "itemListElement": [
          {
            "@type": "ListItem",
            "position": 1,
            "name": "<?= $currentLang === 'ar' ? 'الرئيسية' : 'Home' ?>",
            "item": "https://elnemer-ai.com/megahub/"
          },
          {
            "@type": "ListItem",
            "position": 2,
            "name": "<?= $suiteCurrent ?>",
            "item": "https://elnemer-ai.com/megahub/#suite-<?= $tool['s'] ?>"
          },
          {
            "@type": "ListItem",
            "position": 3,
            "name": "<?= $toolTitle ?>",
            "item": "<?= $canonicalUrl ?>"
          }
        ]
      }
    ]
  }
  </script>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

  <!-- Official Google AdSense Integration -->
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6374003238265902" crossorigin="anonymous"></script>

  <style>
    body { background: #f8fafc !important; color: #0f172a !important; }
    .page-container { max-width: 1100px; margin: 1.5rem auto 3rem auto; padding: 0 1.25rem; }
    .breadcrumbs { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem; flex-wrap: wrap; }
    .breadcrumbs a { color: #0284c7; text-decoration: none; font-weight: 600; }
    .breadcrumbs a:hover { text-decoration: underline; }
    .tool-hero-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2.25rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.06); margin-bottom: 2rem; }
    .tool-badges { display: flex; gap: 0.6rem; margin-bottom: 1rem; flex-wrap: wrap; }
    .badge-suite { background: #e0f2fe; color: #0369a1; padding: 0.3rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; }
    .badge-lang { background: #f1f5f9; color: #334155; padding: 0.3rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-family: 'JetBrains Mono', monospace; font-weight: 700; border: 1px solid #cbd5e1; }
    .tool-hero-title { font-size: 2.2rem; font-weight: 900; color: #0f172a; line-height: 1.3; margin-bottom: 0.5rem; }
    .tool-id-name { font-family: 'JetBrains Mono', monospace; font-size: 1rem; color: #0284c7; background: #f0f9ff; display: inline-block; padding: 0.25rem 0.75rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #bae6fd; }
    .tool-desc-lead { font-size: 1.15rem; line-height: 1.8; color: #334155; margin-bottom: 2rem; }
    .content-section { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 2rem; box-shadow: 0 2px 4px rgba(0,0,0,0.04); margin-bottom: 2rem; }
    .content-section h3 { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.75rem; }
    .features-list { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 1rem; }
    .feature-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; }
    .feature-item h4 { font-size: 1rem; font-weight: 700; color: #0284c7; margin-bottom: 0.35rem; }
    .feature-item p { font-size: 0.9rem; color: #64748b; line-height: 1.5; }
    .terminal-box { background: #0f172a; color: #f8fafc; border-radius: 12px; padding: 1.25rem; font-family: 'JetBrains Mono', monospace; position: relative; margin-bottom: 1.25rem; direction: ltr; text-align: left; overflow-x: auto; }
    .terminal-box code { color: #38bdf8; font-size: 0.95rem; display: block; }
    .terminal-copy-btn { position: absolute; top: 0.75rem; right: 0.75rem; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #ffffff; padding: 0.3rem 0.65rem; border-radius: 6px; font-size: 0.75rem; cursor: pointer; }
    .terminal-copy-btn:hover { background: rgba(255,255,255,0.3); }
    .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; margin-top: 1rem; }
    .related-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.25rem; text-decoration: none; color: inherit; transition: all 0.2s ease; display: flex; flex-direction: column; }
    .related-card:hover { border-color: #0284c7; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(2, 132, 199, 0.1); }
    .related-card h5 { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 0.4rem; }
    .related-card span { font-size: 0.8rem; color: #64748b; font-family: 'JetBrains Mono', monospace; }
    .ad-slot-wrapper { max-width: 100%; margin: 1.5rem 0; text-align: center; }
    .ad-slot-box { background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 0.85rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04); min-height: 90px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .ad-disclosure { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; font-weight: 700; margin-bottom: 0.35rem; display: block; }
    .lang-btn { background: #ffffff; border: 1px solid #cbd5e1; color: #0f172a; padding: 0.45rem 0.85rem; border-radius: 8px; font-size: 0.85rem; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; }
    .lang-btn:hover { border-color: #0284c7; color: #0284c7; }
  </style>
</head>
<body>

  <!-- Header -->
  <header class="navbar" style="background:#ffffff; border-bottom:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
    <div class="nav-container" style="max-width:1100px; margin:0 auto; padding:0.85rem 1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem;">
      <a href="index.html" class="brand" style="display:flex; align-items:center; gap:0.75rem; text-decoration:none; color:inherit;">
        <div class="logo-badge" style="background:#e0f2fe; padding:0.4rem 0.6rem; border-radius:10px; font-size:1.5rem;">🏛️</div>
        <div>
          <h1 class="brand-title" style="font-size:1.15rem; font-weight:800; color:#0f172a; margin:0;">
            <?= $currentLang === 'ar' ? 'موسوعة النمر البرمجية' : 'ElNeMeR AI Encyclopedia' ?>
          </h1>
          <p class="brand-sub" style="font-size:0.75rem; color:#64748b; margin:0;">
            <?= $currentLang === 'ar' ? 'المهندس محمود بك النمر · Mega Hub' : 'Eng. Mahmoud Bek ElNemer · Mega Hub' ?>
          </p>
        </div>
      </a>

      <!-- Actions & Language Switcher -->
      <div style="display:flex; gap:0.65rem; align-items:center;">
        <!-- Dual Language Toggle -->
        <?php if ($currentLang === 'ar'): ?>
          <a href="<?= $urlEn ?>" class="lang-btn" title="Switch to English">
            🌐 English
          </a>
        <?php else: ?>
          <a href="<?= $urlAr ?>" class="lang-btn" title="التحويل للغة العربية">
            🌐 العربية
          </a>
        <?php endif; ?>

        <a href="index.html" class="btn btn-outline" style="border:1px solid #cbd5e1; padding:0.45rem 0.9rem; border-radius:8px; font-size:0.85rem; font-weight:700; color:#0284c7; background:#ffffff;">
          🔍 <?= $currentLang === 'ar' ? 'فهرس الأدوات' : 'Tools Directory' ?>
        </a>
        <a href="<?= $repoUrl ?>" target="_blank" rel="noopener" class="btn btn-outline" style="border:1px solid #0284c7; background:#0284c7; color:#ffffff; padding:0.45rem 0.9rem; border-radius:8px; font-size:0.85rem; font-weight:700;">
          GitHub ↗
        </a>
      </div>
    </div>
  </header>

  <!-- Page Content -->
  <div class="page-container">

    <!-- Breadcrumbs -->
    <nav class="breadcrumbs" aria-label="Breadcrumb">
      <a href="index.html"><?= $currentLang === 'ar' ? 'الرئيسية (Mega Hub)' : 'Home (Mega Hub)' ?></a>
      <span>/</span>
      <span><?= $suiteCurrent ?></span>
      <span>/</span>
      <span style="color:#0f172a; font-weight:700;"><?= $toolTitle ?></span>
    </nav>

    <!-- TOP LEADERBOARD AD UNIT -->
    <div class="ad-slot-wrapper">
      <div class="ad-slot-box">
        <span class="ad-disclosure"><?= $currentLang === 'ar' ? 'إعلان ممول / Sponsored' : 'Sponsored Advertisement' ?></span>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-6374003238265902"
             data-ad-slot="2872380691"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </div>
    </div>

    <!-- Main Tool Hero Card -->
    <section class="tool-hero-card">
      <div class="tool-badges">
        <span class="badge-suite"><?= $suiteCurrent ?></span>
        <span class="badge-lang"><?= $langUpper ?></span>
        <span class="badge-suite" style="background:#dcfce7; color:#15803d;">v1.0.0 Stable</span>
        <span class="badge-suite" style="background:#fef3c7; color:#b45309;">MIT License</span>
      </div>

      <h1 class="tool-hero-title"><?= $toolTitle ?></h1>
      <div class="tool-id-name"><?= $toolSafeName ?></div>

      <p class="tool-desc-lead">
        <?php if ($currentLang === 'ar'): ?>
          <?= $descAr ?> تم تصميم وتطوير هذه الأداة بأعلى معايير الأداء والاعتمادية من قِبل <b>المهندس محمود بك النمر</b> لتكون قابلة للتشغيل المباشر كحزمة مستقلة أو دمجها في خطوط الإنتاج والأنظمة السحابية.
        <?php else: ?>
          <?= $descEn ?> Engineered and maintained with rigorous performance and deterministic standards by <b>Eng. Mahmoud Bek ElNemer</b> for standalone CLI deployment or direct cloud microservice integration.
        <?php endif; ?>
      </p>

      <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
        <button class="btn" onclick="copyText('<?= $cloneCmd ?>', this)" style="background:#0284c7; color:#ffffff; border:none; padding:0.65rem 1.25rem; border-radius:10px; font-weight:700; cursor:pointer; font-size:0.95rem;">
          📋 <?= $currentLang === 'ar' ? 'نسخ أمر Clone' : 'Copy Git Clone' ?>
        </button>
        <a href="<?= $repoUrl ?>" target="_blank" rel="noopener" style="background:#f1f5f9; color:#0f172a; border:1px solid #cbd5e1; padding:0.65rem 1.25rem; border-radius:10px; font-weight:700; text-decoration:none; font-size:0.95rem;">
          <?= $currentLang === 'ar' ? 'المستودع الكامل على GitHub ↗' : 'View on GitHub ↗' ?>
        </a>
      </div>
    </section>

    <!-- IN-ARTICLE MID-CONTENT AD UNIT -->
    <div class="ad-slot-wrapper">
      <div class="ad-slot-box">
        <span class="ad-disclosure"><?= $currentLang === 'ar' ? 'إعلان ممول / Sponsored' : 'Sponsored Advertisement' ?></span>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-6374003238265902"
             data-ad-slot="2872380691"
             data-ad-format="horizontal"
             data-full-width-responsive="true"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </div>
    </div>

    <!-- Quick Installation & Setup Section -->
    <section class="content-section">
      <h3>🚀 <?= $currentLang === 'ar' ? 'التثبيت والتشغيل السريع (Quick Installation & Build)' : 'Quick Installation & Build Guide' ?></h3>
      <p style="color:#64748b; margin-bottom:1rem; font-size:0.95rem;">
        <?= $currentLang === 'ar' ? 'يمكنك استنساخ المستودع وبدء استخدام الأداة مباشرة في بيئة عملك عبر الأوامر التالية:' : 'Clone the repository and run the package locally with one command:' ?>
      </p>

      <div style="margin-bottom:1rem;">
        <label style="display:block; font-weight:700; font-size:0.85rem; color:#475569; margin-bottom:0.35rem;">
          <?= $currentLang === 'ar' ? '1. استنساخ المستودع عبر Git:' : '1. Clone via Git:' ?>
        </label>
        <div class="terminal-box">
          <code><?= $cloneCmd ?></code>
          <button class="terminal-copy-btn" onclick="copyText('<?= $cloneCmd ?>', this)"><?= $currentLang === 'ar' ? 'نسخ' : 'Copy' ?></button>
        </div>
      </div>

      <div style="margin-bottom:1rem;">
        <label style="display:block; font-weight:700; font-size:0.85rem; color:#475569; margin-bottom:0.35rem;">
          <?= $currentLang === 'ar' ? "2. الانتقال للمجلد والتثبيت ({$langUpper}):" : "2. Build & Install ({$langUpper}):" ?>
        </label>
        <div class="terminal-box">
          <code>cd <?= $toolSafeName ?> && <?= $installCmd ?></code>
          <button class="terminal-copy-btn" onclick="copyText('cd <?= $toolSafeName ?> && <?= $installCmd ?>', this)"><?= $currentLang === 'ar' ? 'نسخ' : 'Copy' ?></button>
        </div>
      </div>
    </section>

    <!-- Features & Technical Specifications -->
    <section class="content-section">
      <h3>⚙️ <?= $currentLang === 'ar' ? 'المواصفات التقنية والمعمارية' : 'Technical Specifications & Architecture' ?></h3>
      <div class="features-list">
        <div class="feature-item">
          <h4>⚡ <?= $currentLang === 'ar' ? 'أداء واستقرار فائق' : 'Extreme Performance & Throughput' ?></h4>
          <p><?= $currentLang === 'ar' ? "مكتوبة ومحسنة بلغة {$langUpper} لتوفير سرعة معالجة عالية واستهلاك ذاكرة منخفض." : "Engineered in native {$langUpper} for zero-cost abstractions, predictable execution, and minimal memory allocation." ?></p>
        </div>
        <div class="feature-item">
          <h4>🔒 <?= $currentLang === 'ar' ? 'حماية وأمان معتمد' : 'Deterministic & Secure Design' ?></h4>
          <p><?= $currentLang === 'ar' ? 'تخضع لمعايير أمنية صارمة واختبارات تكامل دورية لضمان خلوها من الثغرات واستقرار الاستدعاء.' : 'Strictly audited against common CVEs and injection attacks with automated regression tests.' ?></p>
        </div>
        <div class="feature-item">
          <h4>📦 <?= $currentLang === 'ar' ? 'تكامل معياري وسهل' : 'Modular Component Integration' ?></h4>
          <p><?= $currentLang === 'ar' ? 'يمكن استيرادها كمكتبة مستقلة أو تشغيلها عبر واجهات سطر الأوامر (CLI) أو خدمات الويب.' : 'Ready to be imported as a dependency or spawned as a standalone microservice or CLI tool.' ?></p>
        </div>
        <div class="feature-item">
          <h4>🌍 <?= $currentLang === 'ar' ? 'دعم كامل لمنصات التشغيل' : 'Cross-Platform Compatibility' ?></h4>
          <p><?= $currentLang === 'ar' ? 'تعمل بسلاسة تامة على أنظمة Linux, Windows, macOS وبيئات Docker السحابية.' : 'Full support across Linux, Windows, macOS, Docker containers, and serverless environments.' ?></p>
        </div>
      </div>
    </section>

    <!-- HIGH-VALUE MULTIPLEX AD UNIT -->
    <div class="ad-slot-wrapper">
      <div class="ad-slot-box" style="padding:1.25rem;">
        <span class="ad-disclosure" style="color:#0284c7; font-weight:800;">
          <?= $currentLang === 'ar' ? 'إعلانات مقترحة للمطورين / Recommended Developer Ads' : 'Recommended Developer Ads' ?>
        </span>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-format="autorelaxed"
             data-ad-client="ca-pub-6374003238265902"
             data-ad-slot="2872380691"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </div>
    </div>

    <!-- Related Tools Section -->
    <?php if (!empty($related)): ?>
    <section class="content-section">
      <h3>🔗 <?= $currentLang === 'ar' ? "أدوات مقترحة ذات صلة في {$suiteAr}" : "Related Tools in {$suiteEn}" ?></h3>
      <p style="color:#64748b; font-size:0.9rem; margin-bottom:1rem;">
        <?= $currentLang === 'ar' ? 'تصفح المزيد من الحلول البرمجية الشقيقة ضمن نفس الحزمة التخصصية:' : 'Explore sister packages and tools within the same specialized suite:' ?>
      </p>
      <div class="related-grid">
        <?php foreach ($related as $rel): ?>
          <a href="tool.php?name=<?= urlencode($rel['n']) ?><?= $currentLang === 'en' ? '&lang=en' : '' ?>" class="related-card">
            <h5><?= htmlspecialchars($rel['t']) ?></h5>
            <span><?= htmlspecialchars($rel['n']) ?></span>
            <div style="margin-top:auto; padding-top:0.75rem; font-size:0.8rem; color:#0284c7; font-weight:700;">
              <?= $currentLang === 'ar' ? 'عرض الشرح والمواصفات ←' : 'View Documentation →' ?>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

    <!-- BOTTOM ANCHOR AD UNIT -->
    <div class="ad-slot-wrapper">
      <div class="ad-slot-box">
        <span class="ad-disclosure"><?= $currentLang === 'ar' ? 'إعلان ممول / Sponsored' : 'Sponsored Advertisement' ?></span>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-6374003238265902"
             data-ad-slot="2872380691"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </div>
    </div>

  </div>

  <!-- Footer -->
  <footer class="footer" style="background:#ffffff; border-top:1px solid #e2e8f0; padding:2.5rem 1rem; text-align:center;">
    <div class="footer-container" style="max-width:1100px; margin:0 auto; color:#64748b; font-size:0.9rem; font-weight:600;">
      <p>© 2026 Eng. Mahmoud Bek ElNemer (المهندس محمود بك النمر) · Nemer Digital Solutions</p>
      <p style="margin-top:0.5rem;">
        <a href="index.html" style="color:#0284c7; text-decoration:none;"><?= $currentLang === 'ar' ? 'فهرس الأدوات الشامل' : 'Mega Directory' ?></a> ·
        <a href="https://elnemer-ai.com" target="_blank" style="color:#0284c7; text-decoration:none;">elnemer-ai.com</a> ·
        <a href="https://github.com/ElNeMeR70" target="_blank" style="color:#0284c7; text-decoration:none;">GitHub (@ElNeMeR70)</a>
      </p>
    </div>
  </footer>

  <script>
    function copyText(txt, btn) {
      navigator.clipboard.writeText(txt).then(() => {
        const orig = btn.innerText;
        btn.innerText = "<?= $currentLang === 'ar' ? '✅ تم النسخ!' : '✅ Copied!' ?>";
        setTimeout(() => { btn.innerText = orig; }, 1500);
      });
    }
  </script>
</body>
</html>
