# Next Steps

## 1. Register Extension in Root Composer

Add the extension as a path repository and require it in the root `composer.json`:

```json
{
    "repositories": [
        { "type": "path", "url": "packages/typo3-extension-testimonials" }
    ],
    "require": {
        "maispace/mai-testimonials": "@dev"
    }
}
```

Then run `ddev composer update maispace/mai-testimonials`.

## 2. Activate Extension in TYPO3

In the TYPO3 backend: **Admin Tools → Extensions → Activate `mai_testimonials`**.

Or via CLI inside DDEV:

```bash
ddev exec vendor/bin/typo3 extension:activate mai_testimonials
```

## 3. Apply Database Schema

Run the database analyser to create `tx_maitestimonials_testimonial`:

```bash
ddev exec vendor/bin/typo3 database:updateschema
```

## 4. Include TypoScript / Site Set

Either add the Site Set `maispace/mai-testimonials` to your site configuration, or include the TypoScript manually:

```yaml
# config/sites/<site>/config.yaml
dependencies:
  - maispace/mai-testimonials
```

## 5. Add Content Element to a Page

In the TYPO3 backend, insert one of the three content elements on a test page:

- **Testimonials: Grid** — responsive card layout
- **Testimonials: Slider** — auto-playing carousel
- **Testimonials: Single** — featured single quote

Configure storage page and optional category filter in the FlexForm.

## 6. Create Test Records

Create a few `Testimonial` records in the TYPO3 list module:
- Fill in quote text, author name, and role
- Upload a portrait image (JPG/PNG/WebP, recommended 200×200 px)
- Assign a `sys_category` if category filtering will be used

## 7. QA Pass

Run the full linter suite from inside the extension directory:

```bash
cd packages/typo3-extension-testimonials
composer lint:check
composer test:unit
```

Fix any issues surfaced by phpcs, phpstan (level 5), or typoscript-lint.

## 8. Styling

`mai_testimonials` ships no SCSS. Add component styles in `EXT:mai_theme` (per the architecture constraint — `mai_assets` compiles SCSS exclusively):

- `.mai-testimonial` — base blockquote card
- `.mai-testimonials--grid` — responsive grid layout
- `.mai-testimonials--slider` — carousel track and slide transitions
- `.mai-testimonials--single` — featured/hero quote style
- `.mai-testimonial__portrait` — circular crop, consistent size

## 9. Initialise Git Repository

The extension directory has no git history yet. Initialise and push to GitHub:

```bash
cd packages/typo3-extension-testimonials
git init
git remote add origin https://github.com/mai-space-de/typo3-extension-testimonials.git
git add .
git commit -m "feat: initial scaffold for mai_testimonials"
git push -u origin main
```

Then register it as a submodule in the parent repo:

```bash
# from repo root
git submodule add https://github.com/mai-space-de/typo3-extension-testimonials packages/typo3-extension-testimonials
git add .gitmodules packages/typo3-extension-testimonials
git commit -m "chore: add mai_testimonials submodule"
```

## 10. Story Wall Integration (Future)

Community testimonials submitted via `mai_account` should feed into a moderation queue. Planned approach:

1. Add a `source` field (`enum: manual | community`) to `tx_maitestimonials_testimonial`
2. Add a `moderation_status` field (`enum: pending | approved | rejected`)
3. Extend `TestimonialsController::listAction` to filter `source = manual OR (source = community AND moderation_status = approved)`
4. Add a backend module or list view filter for editors to approve/reject community submissions
5. Wire `mai_account`'s newsletter/profile form to write pending records to this table

## 11. Schema.org Markup (Future)

Wrap rendered testimonials in `<div itemscope itemtype="https://schema.org/Review">` with appropriate `itemprop` attributes (`reviewBody`, `author`, `itemReviewed`) for SEO benefit.
