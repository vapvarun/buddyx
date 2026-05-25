# BuddyX Documentation

Welcome! New to the theme? **Start here, in this order**:

## First-time setup — do this first (~45 minutes)

Every site needs the same basics before you worry about the specific kind of site you're building. Walk through these in order:

> **→ [Quick Start — First-Time Setup](website/getting-started/quick-start.md)**
>
> 9 mandatory steps covering: install the theme → site identity (logo + title) → colors → fonts → header + menu → footer → essential pages (Home, About, Contact, Privacy Policy) → set your homepage → (optional) dark mode.
>
> After this, your site looks professional and is ready for visitors. Then you pick what *kind* of site to build.

## Then — what kind of site are you building?

Once you've finished the first-time setup above, pick the description that fits what you want to build:

> **→ [Choose Your Path](website/getting-started/choose-your-path.md)**

BuddyX is a flexible general-purpose theme built to be community-ready out of the box. Common paths: a personal or company blog, a portfolio, a business landing site, or a community / social site with member profiles and groups (via the free BuddyPress plugin).

> **Want forums, video hosting, gamification, job boards, ads, or an LMS?** Each of these is its own **separate plugin** from Wbcom Designs (same team that builds BuddyX) — and each one has a free version you can install today and a pro version sold separately. The in-house catalog: Jetonomy (community / forums / Q&A), MediaVerse (video), WB Gamification (points + badges), WP Career Board (jobs), WP Ads Manager (ads), plus Listora (directories) and Learnomy (LMS) coming soon. **BuddyX Pro** (the theme) is also a separate product — it adds presets, sign-in popup, per-page settings, and more customization. Browse the full catalog at [wbcomdesigns.com](https://wbcomdesigns.com/downloads/).

## Recipes — focused walkthroughs for specific outcomes

Step-by-step recipes for common goals that span multiple settings:

| Recipe | What you'll build |
|---|---|
| [Match the demo design](website/recipes/match-the-demo.md) | Your site looks like the published BuddyX demo |
| [Customize your colors and fonts](website/recipes/customize-brand.md) | A site that reflects your brand colors and typography choice |

See [all recipes](website/recipes/) for the full index.

---

## Other ways into the docs

### Just getting started (alternate entry points)

| What you want | Where to go |
|---|---|
| **I want my site to look like the demo / sales-page screenshots** | [Quick Start](website/getting-started/quick-start.md) + [Match the demo](website/recipes/match-the-demo.md) |
| **What's a "Customizer"? What's a "theme" vs a "plugin"?** | [Glossary](website/faq-support/glossary.md) — plain-English definitions of every term in these docs |
| **I want to upgrade to BuddyX Pro** | [BuddyX Pro at wbcomdesigns.com](https://wbcomdesigns.com/downloads/buddyx-theme/) — paid theme that adds 14 color presets, 7 typography presets, sign-in popup, per-page settings, and dark customizer surface |
| **I'm looking for a specific feature plugin (forums, video, jobs, ads, gamification, LMS)** | [Wbcom plugin catalog](https://wbcomdesigns.com/downloads/) — each plugin is sold separately, free version + pro version. Same team that builds BuddyX |

### Browse by topic

| Topic | Where to find it |
|---|---|
| **Site setup + install + quick-start** | [`website/getting-started/`](website/getting-started/) — intro, installation, quick-start, choose-your-path |
| **Colors + dark mode** | [`website/skin-colors/`](website/skin-colors/) — color-scheme reference + dark mode reference |
| **Recipes (focused walkthroughs)** | [`website/recipes/`](website/recipes/) — match-the-demo, customize-brand |
| **FAQ + troubleshooting + glossary** | [`website/faq-support/`](website/faq-support/) — faqs, troubleshooting, glossary |

## For developers + maintainers

| Path | What's there |
|---|---|
| **[`buddyx-design-tokens.md`](buddyx-design-tokens.md)** | CSS custom-property token reference (`--bx-color-*` etc.) |
| **[`COVERAGE_MATRIX.md`](COVERAGE_MATRIX.md)** | Customizer-section → plan-doc mapping for drift detection |
| **[`local-ci.md`](local-ci.md)** | Pre-commit hooks + manual command set |
| **[`customizer-inventory-snapshot.txt`](customizer-inventory-snapshot.txt)** | All 125 customizer field declarations across 18 sections — committed for PR-time drift detection |
| **[`../plans/`](../plans/)** | Design + implementation plans (active + archived by release) |
| **[`../CLAUDE.md`](../CLAUDE.md)** | Project-local working rules |
| **[`../CONTRIBUTING.md`](../CONTRIBUTING.md)** | Contribution workflow |
| **[`../README.md`](../README.md)** | Repo-level project intro |
