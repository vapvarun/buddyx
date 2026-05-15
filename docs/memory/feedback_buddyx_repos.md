---
name: BuddyX has two GitHub repos — develop on wbcomdesigns, release on vapvarun
description: Development always goes to wbcomdesigns/buddyx (private). vapvarun/buddyx (public) is a release-only mirror for wp.org and customer downloads — never push dev branches or feature commits there
type: project
originSessionId: 101e1337-3bd5-4c7f-9bd1-bdb39c4962c2
---
BuddyX has two GitHub remotes that look interchangeable but aren't:

- **wbcomdesigns/buddyx** (private) — the development repo. All feature branches, bug fixes, work-in-progress. `origin` points here.
- **vapvarun/buddyx** (public) — release-only mirror. Customer-facing. Used for wp.org submission, GitHub Releases for download, and as the link in `readme.txt`.

**Why:** Development happens privately so unfinished work, internal discussions, CI configurations, and security-related commits stay out of the public eye. Customers only see polished tagged releases.

**How to apply:**
- Push dev branches (5.0.3, 5.1.0, feature/*, fix/*) only to `origin` (wbcomdesigns).
- Push to `vapvarun` only when cutting a release: master + version tag, then create the GitHub Release with the clean zip attached.
- The `readme.txt` GitHub link points to `vapvarun/buddyx` (the public-facing repo customers should see).
- Don't run `git push public` for dev branches. Don't add public as a default upstream.

If a sandbox prompt asks to add `vapvarun` as a remote during a non-release task, decline.
